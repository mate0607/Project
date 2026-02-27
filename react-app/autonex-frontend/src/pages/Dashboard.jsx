import React, { useEffect, useState } from "react";
import { api } from "../api.js";
import { Link } from "react-router-dom";

export default function Dashboard() {
  const [cars, setCars] = useState([]);
  const [err, setErr] = useState("");
  const [editingId, setEditingId] = useState(null);
  const [editForm, setEditForm] = useState({ make_model: "", vin: "", year: "" });

  async function load() {
    setErr("");
    try {
      const res = await api.cars();
      setCars(res.cars);
    } catch (e) {
      setErr(e.message);
    }
  }

  useEffect(() => { load(); }, []);

  const handleDelete = async (carId) => {
    if (!window.confirm("Biztosan törölni szeretnéd ezt az autót?")) return;
    
    try {
      await api.deleteCar(carId);
      load();
    } catch (err) {
      alert("Hiba történt a törlés során: " + err.message);
    }
  };

  const startEdit = (car) => {
    setEditingId(car.id);
    setEditForm({ make_model: car.make_model, vin: car.vin, year: car.year || "" });
  };

  const cancelEdit = () => {
    setEditingId(null);
    setEditForm({ make_model: "", vin: "", year: "" });
  };

  const handleUpdate = async (carId) => {
    try {
      await api.updateCar(carId, editForm.make_model, editForm.vin, editForm.year);
      setEditingId(null);
      load();
    } catch (err) {
      alert("Hiba történt a frissítés során: " + err.message);
    }
  };

  return (
    <div style={{ maxWidth: 1000, margin: "30px auto", padding: "0 20px" }}>
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 30 }}>
        <h2 style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
          Járműveim
        </h2>
        <Link to="/cars/new">
          <button style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            Új autó hozzáadása
          </button>
        </Link>
      </div>

      {err && <div className="alert alert-error">{err}</div>}

      {cars.length === 0 ? (
        <div className="card" style={{ textAlign: 'center', padding: '60px 40px' }}>
          <div style={{ fontSize: '4rem', marginBottom: '20px', opacity: 0.5 }}></div>
          <h3 style={{ marginBottom: '12px', color: '#94A3B8' }}>Még nincs járműved</h3>
          <p style={{ color: '#64748B', marginBottom: '24px' }}>
            Kezdd el a járműkezelést az első autód hozzáadásával
          </p>
          <Link to="/cars/new">
            <button>Autó hozzáadása</button>
          </Link>
        </div>
      ) : (
        <div style={{ display: 'grid', gap: '20px' }}>
          {cars.map(c => (
            <div key={c.id} className="card" style={{ padding: '24px' }}>
              {editingId === c.id ? (
                <div>
                  <h3 style={{ margin: '0 0 16px 0', fontSize: '1.125rem' }}>Autó szerkesztése</h3>
                  <div style={{ display: 'grid', gap: '12px', marginBottom: '16px' }}>
                    <div>
                      <label style={{ display: 'block', color: '#94A3B8', fontSize: '0.875rem', marginBottom: '4px' }}>Típus</label>
                      <input 
                        type="text"
                        value={editForm.make_model}
                        onChange={(e) => setEditForm({...editForm, make_model: e.target.value})}
                        placeholder="pl. Toyota Corolla"
                        style={{ width: '100%' }}
                      />
                    </div>
                    <div>
                      <label style={{ display: 'block', color: '#94A3B8', fontSize: '0.875rem', marginBottom: '4px' }}>VIN (17 karakter)</label>
                      <input 
                        type="text"
                        value={editForm.vin}
                        onChange={(e) => setEditForm({...editForm, vin: e.target.value.toUpperCase()})}
                        maxLength="17"
                        style={{ width: '100%' }}
                      />
                    </div>
                    <div>
                      <label style={{ display: 'block', color: '#94A3B8', fontSize: '0.875rem', marginBottom: '4px' }}>Évjárat</label>
                      <input 
                        type="number"
                        value={editForm.year}
                        onChange={(e) => setEditForm({...editForm, year: e.target.value})}
                        placeholder="2020"
                        style={{ width: '100%' }}
                      />
                    </div>
                  </div>
                  <div style={{ display: 'flex', gap: '8px' }}>
                    <button onClick={() => handleUpdate(c.id)} style={{ padding: '8px 16px', fontSize: '14px' }}>
                      Mentés
                    </button>
                    <button onClick={cancelEdit} className="secondary" style={{ padding: '8px 16px', fontSize: '14px' }}>
                      Mégse
                    </button>
                  </div>
                </div>
              ) : (
                <div style={{ 
                  display: 'flex',
                  justifyContent: 'space-between',
                  alignItems: 'flex-start',
                  gap: '20px'
                }}>
                  <div style={{ flex: 1 }}>
                    <div style={{ 
                      fontWeight: 700,
                      fontSize: '1.25rem',
                      marginBottom: '8px',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '10px'
                    }}>
                      <Link to={`/cars/${c.id}`} style={{ 
                        color: '#818CF8',
                        textDecoration: 'none'
                      }}>
                        {c.make_model}
                      </Link>
                    </div>
                    <div style={{ 
                      color: '#94A3B8',
                      fontSize: '0.875rem',
                      display: 'flex',
                      gap: '20px',
                      flexWrap: 'wrap'
                    }}>
                      <span>VIN: <strong style={{ color: '#CBD5E1' }}>{c.vin}</strong></span>
                      {c.year && <span>Év: <strong style={{ color: '#CBD5E1' }}>{c.year}</strong></span>}
                    </div>
                  </div>
                  
                  {c.image_url && (
                    <div style={{ marginTop: '2px' }}>
                      <img src={`http://localhost:4000${c.image_url}`} alt={c.make_model} style={{ maxWidth: '120px', maxHeight: '100px', borderRadius: '6px', objectFit: 'cover' }} />
                    </div>
                  )}
                  
                  <div style={{ display: 'flex', gap: '8px', alignItems: 'flex-start' }}>
                    <Link to={`/cars/${c.id}`}>
                      <button style={{ 
                        padding: '10px 20px',
                        fontSize: '14px'
                      }}>
                        Részletek →
                      </button>
                    </Link>
                    <button 
                      onClick={() => startEdit(c)}
                      style={{ 
                        padding: '10px 16px', 
                        fontSize: '14px',
                        background: 'rgba(129, 140, 248, 0.2)',
                        border: '1px solid rgba(129, 140, 248, 0.3)',
                        color: '#818CF8'
                      }}
                    >
                      Szerkeszt
                    </button>
                    <button 
                      onClick={() => handleDelete(c.id)}
                      style={{ 
                        padding: '10px 16px', 
                        fontSize: '14px',
                        background: 'rgba(239, 68, 68, 0.2)',
                        border: '1px solid rgba(239, 68, 68, 0.3)',
                        color: '#EF4444'
                      }}
                    >
                      Törlés
                    </button>
                  </div>
                </div>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}