import React, { useEffect, useState } from "react";
import { api } from "../api.js";
import { useParams, Link, useNavigate } from "react-router-dom";
import CarImageUpload from "../components/CarImageUpload.jsx";

export default function CarDetail() {
  const { carId } = useParams();
  const nav = useNavigate();
  const [car, setCar] = useState(null);
  const [issues, setIssues] = useState([]);
  const [err, setErr] = useState("");

  // issue form
  const [category, setCategory] = useState("engine");
  const [urgency, setUrgency] = useState("medium");
  const [description, setDescription] = useState("");

  async function load() {
    setErr("");
    try {
      const res = await api.carDetail(carId);
      setCar(res.car);
      setIssues(res.issues);
    } catch (e) {
      setErr(e.message);
    }
  }

  useEffect(() => { load(); }, [carId]);

  async function addIssue(e) {
    e.preventDefault();
    setErr("");
    try {
      const res = await api.createIssue(carId, category, description, urgency);
      nav(`/recommendations/${res.issueId}`);
    } catch (e) {
      setErr(e.message);
    }
  }

  if (!car) return <div style={{ padding: 16, color: '#94A3B8' }}>{err || "Betöltés..."}</div>;

  return (
    <div style={{ maxWidth: 900, margin: "30px auto", padding: "0 20px" }}>
      <div className="card">
        <h2>{car.make_model}</h2>
        <div style={{ color: "#94A3B8", marginBottom: '20px' }}>
          VIN: {car.vin}{car.year ? ` | Év: ${car.year}` : ""}
        </div>
        
        {car.image_url && (
          <div style={{ marginBottom: '20px' }}>
            <img src={`http://localhost:4000${car.image_url}`} alt={car.make_model} style={{ maxWidth: '300px', maxHeight: '300px', borderRadius: '8px' }} />
          </div>
        )}

        <CarImageUpload 
          carId={carId} 
          currentImageUrl={car.image_url}
          onImageUploaded={(imageUrl) => {
            setCar({ ...car, image_url: imageUrl });
          }}
          onImageDeleted={() => {
            setCar({ ...car, image_url: null });
          }}
        />
      </div>

      <div className="card">
        <h3>Új hiba</h3>
        {err && <div className="alert alert-error">{err}</div>}
        <form onSubmit={addIssue}>
          <div>
            <label>Kategória</label>
            <select value={category} onChange={(e) => setCategory(e.target.value)}>
              <option value="engine">Motor</option>
              <option value="brake">Fék</option>
              <option value="suspension">Felfüggesztés</option>
              <option value="electric">Elektromosság</option>
              <option value="transmission">Sebességváltó</option>
              <option value="cooling">Hűtési rendszer</option>
              <option value="exhaust">Kipufogó rendszer</option>
              <option value="fuel">Üzemanyag rendszer</option>
              <option value="steering">Kormányzás</option>
              <option value="suspension">Futómű</option>
              <option value="tires">Gumiabroncsok</option>
              <option value="battery">Akkumulátor</option>
              <option value="alternator">Generátor</option>
              <option value="starter">Önindító</option>
              <option value="lights">Fények</option>
              <option value="wipers">Ablaktörlők</option>
              <option value="air-conditioning">Légkondi</option>
              <option value="interior">Belső rész</option>
              <option value="exterior">Külső rész</option>
              <option value="other">Egyéb</option>
            </select>
          </div>

          <div>
            <label>Sürgősség</label>
            <select value={urgency} onChange={(e) => setUrgency(e.target.value)}>
              <option value="low">Alacsony</option>
              <option value="medium">Közepes</option>
              <option value="high">Nagy</option>
            </select>
          </div>

          <div>
            <label>Leírás</label>
            <textarea 
              value={description} 
              onChange={(e) => setDescription(e.target.value)} 
              rows={5}
              placeholder="Írja le a hiba részleteit..."
              required
            />
          </div>

          <button type="submit" style={{ marginTop: '10px' }}>Mentés + Ajánlás</button>
        </form>
      </div>

      <div style={{ marginTop: 30 }}>
        <h3 style={{ marginBottom: '20px' }}>Korábbi hibák</h3>
        {issues.length === 0 ? (
          <div className="card" style={{ textAlign: 'center', padding: '40px' }}>
            <p style={{ color: '#94A3B8' }}>Még nincs rögzített hiba.</p>
          </div>
        ) : (
          issues.map(i => (
            <div key={i.id} className="card">
              <div style={{ marginBottom: '12px' }}>
                <strong style={{ color: '#818CF8' }}>{i.category}</strong>
                <span className={`badge badge-${i.urgency === 'high' ? 'danger' : i.urgency === 'low' ? 'info' : 'warning'}`} 
                  style={{ marginLeft: '12px', textTransform: 'capitalize' }}>
                  {i.urgency}
                </span>
              </div>
              <div style={{ color: '#CBD5E1', marginBottom: '12px' }}>{i.description}</div>
              <div style={{ color: '#94A3B8', marginBottom: '12px' }}>Ajánlás: {i.service_name || "—"}</div>
              {i.service_name && (
                <Link to={`/recommendations/${i.id}`}>
                  <button style={{ padding: '8px 16px', fontSize: '14px' }}>Részletek</button>
                </Link>
              )}
            </div>
          ))
        )}
      </div>
    </div>
  );
}