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

  // sale form
  const [showSaleForm, setShowSaleForm] = useState(false);
  const [saleStatus, setSaleStatus] = useState(null);
  const [salePrice, setSalePrice] = useState("");
  const [saleDescription, setSaleDescription] = useState("");
  const [saleCondition, setSaleCondition] = useState("good");
  const [saleMileage, setSaleMileage] = useState("");
  const [savingSale, setSavingSale] = useState(false);

  async function load() {
    setErr("");
    try {
      const res = await api.carDetail(carId);
      setCar(res.car);
      setIssues(res.issues);
      
      // Load sale status
      const saleRes = await api.getCarSaleStatus(carId);
      setSaleStatus(saleRes.sale);
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

  async function handleListForSale(e) {
    e.preventDefault();
    if (!salePrice) {
      setErr("Az ár kötelező");
      return;
    }
    
    setSavingSale(true);
    setErr("");
    try {
      await api.listCarForSale(carId, parseFloat(salePrice), saleDescription, saleCondition, saleMileage ? parseInt(saleMileage) : null);
      const saleRes = await api.getCarSaleStatus(carId);
      setSaleStatus(saleRes.sale);
      setShowSaleForm(false);
      setSalePrice("");
      setSaleDescription("");
      setSaleCondition("good");
      setSaleMileage("");
      setErr("");
    } catch (e) {
      setErr(e.message);
    } finally {
      setSavingSale(false);
    }
  }

  async function handleRemoveFromSale() {
    if (!window.confirm("Biztosan leveszed az autót az eladási listáról?")) return;
    
    setErr("");
    try {
      await api.removeCarFromSale(carId);
      setSaleStatus(null);
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
        <h3>Eladásra bocsájtás</h3>
        {err && <div className="alert alert-error">{err}</div>}
        {saleStatus ? (
          <div style={{ padding: '20px', borderRadius: '8px', background: 'rgba(34, 197, 94, 0.1)', borderLeft: '4px solid #22C55E' }}>
            <h4 style={{ color: '#22C55E', marginBottom: '10px' }}>✓ Az autó eladásra fel van sorolva</h4>
            <p style={{ color: '#CBD5E1', marginBottom: '12px' }}><strong>Ár:</strong> {saleStatus.price} Ft</p>
            {saleStatus.car_condition && <p style={{ color: '#CBD5E1', marginBottom: '12px' }}><strong>Állapot:</strong> {saleStatus.car_condition}</p>}
            {saleStatus.mileage && <p style={{ color: '#CBD5E1', marginBottom: '12px' }}><strong>Futásteljesítmény:</strong> {saleStatus.mileage} km</p>}
            {saleStatus.description && <p style={{ color: '#CBD5E1', marginBottom: '12px' }}><strong>Leírás:</strong> {saleStatus.description}</p>}
            <p style={{ color: '#94A3B8', fontSize: '12px', marginBottom: '16px' }}>Felsorolt: {new Date(saleStatus.created_at).toLocaleDateString('hu-HU')}</p>
            <button 
              onClick={handleRemoveFromSale}
              style={{
                padding: '10px 16px',
                background: 'rgba(239, 68, 68, 0.2)',
                border: '1px solid rgba(239, 68, 68, 0.4)',
                color: '#EF4444',
                borderRadius: '6px',
                cursor: 'pointer',
                fontWeight: 500
              }}
            >
              Levétel az eladási listáról
            </button>
          </div>
        ) : (
          <>
            <button 
              onClick={() => setShowSaleForm(!showSaleForm)}
              style={{
                padding: '10px 16px',
                background: 'rgba(79, 70, 229, 0.15)',
                border: '1px solid rgba(129, 140, 248, 0.3)',
                color: '#818CF8',
                borderRadius: '6px',
                cursor: 'pointer',
                fontWeight: 500,
                marginBottom: showSaleForm ? '16px' : '0'
              }}
            >
              {showSaleForm ? '✕ Mégse' : '+ Autó eladásra bocsájtása'}
            </button>

            {showSaleForm && (
              <form onSubmit={handleListForSale} style={{ marginTop: '16px' }}>
                <div>
                  <label>Ár (Ft) *</label>
                  <input 
                    type="number" 
                    value={salePrice}
                    onChange={(e) => setSalePrice(e.target.value)}
                    placeholder="Pl: 3500000"
                    required
                    style={{ width: '100%' }}
                  />
                </div>

                <div>
                  <label>Autó állapota</label>
                  <select value={saleCondition} onChange={(e) => setSaleCondition(e.target.value)}>
                    <option value="excellent">Kiváló</option>
                    <option value="good">Jó</option>
                    <option value="fair">Elfogadható</option>
                    <option value="needs-repair">Javítást igényel</option>
                  </select>
                </div>

                <div>
                  <label>Futásteljesítmény (km)</label>
                  <input 
                    type="number"
                    value={saleMileage}
                    onChange={(e) => setSaleMileage(e.target.value)}
                    placeholder="Pl: 150000"
                    style={{ width: '100%' }}
                  />
                </div>

                <div>
                  <label>Leírás</label>
                  <textarea 
                    value={saleDescription}
                    onChange={(e) => setSaleDescription(e.target.value)}
                    rows={4}
                    placeholder="Írj le információkat az autóról (pl. felújítások, hiányosságok, stb.)"
                    style={{ width: '100%' }}
                  />
                </div>

                <button 
                  type="submit"
                  disabled={savingSale}
                  style={{
                    marginTop: '10px',
                    opacity: savingSale ? 0.6 : 1,
                    cursor: savingSale ? 'not-allowed' : 'pointer'
                  }}
                >
                  {savingSale ? 'Mentés...' : 'Autó eladásra felsorolása'}
                </button>
              </form>
            )}
          </>
        )}
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