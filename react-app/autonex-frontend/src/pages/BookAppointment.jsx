import { useState, useEffect } from "react";
import { api } from "../api.js";
import { useNavigate } from "react-router-dom";

export default function BookAppointment() {
  const [cars, setCars] = useState([]);
  const [loading, setLoading] = useState(true);
  const [car_id, setCarId] = useState("");
  const [date, setDate] = useState("");
  const [time, setTime] = useState("");
  const [service, setService] = useState("");
  const [msg, setMsg] = useState("");
  const [error, setError] = useState(null);
  const [validationErrors, setValidationErrors] = useState({});
  const [successMessage, setSuccessMessage] = useState(null);
  const [submitting, setSubmitting] = useState(false);
  const nav = useNavigate();

  useEffect(() => {
    api.cars()
      .then(res => {
        setCars(res.cars || []);
        setLoading(false);
        setError(null);
      })
      .catch(err => {
        console.error('Autók betöltési hiba:', err);
        setError('Nem sikerült az autók betöltése. Kérjük, próbálja meg később.');
        setCars([]);
        setLoading(false);
      });
  }, []);

  const validateForm = () => {
    const errors = {};
    
    if (!car_id) {
      errors.car_id = 'Járművet kell kiválasztani';
    }
    
    if (!date) {
      errors.date = 'A dátum megadása kötelező';
    } else {
      const selectedDate = new Date(date);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      if (selectedDate < today) {
        errors.date = 'A dátum nem lehet a múltban';
      }
    }
    
    if (!time) {
      errors.time = 'Az idő megadása kötelező';
    } else {
      const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
      if (!timeRegex.test(time)) {
        errors.time = 'Érvénytelen időformátum (HH:MM)';
      }
    }
    
    if (!service) {
      errors.service = 'A szolgáltatás kiválasztása kötelező';
    }
    
    setValidationErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const submit = async (e) => {
    e.preventDefault();
    
    if (!validateForm()) {
      setError('Kérjük, javítsa ki a hibákat az űrlapban');
      return;
    }
    
    setSubmitting(true);
    setError(null);

    try {
      await api.createAppointment(car_id, date, time, service);
      setSuccessMessage("Foglalás sikeresen leadva!");
      setValidationErrors({});
      setTimeout(() => {
        nav(-1);
      }, 1500);
    } catch (err) {
      console.error('Foglalás létrehozási hiba:', err);
      const errorMsg = err.response?.data?.message || err.message || 'Hiba történt a foglalás során. Kérjük, próbálja meg később.';
      setError(errorMsg);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="form-wrapper">
      <h2 style={{ display: 'flex', alignItems: 'center', gap: '12px', justifyContent: 'center' }}>
        Időpont foglalás
      </h2>

      {error && (
        <div style={{ 
          background: 'rgba(239, 68, 68, 0.1)',
          border: '1px solid rgba(239, 68, 68, 0.3)',
          borderRadius: '6px',
          padding: '12px 16px',
          marginBottom: '16px',
          color: '#FCA5A5',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center'
        }}>
          <span>⚠️ {error}</span>
          <button 
            onClick={() => setError(null)}
            style={{ background: 'none', border: 'none', color: '#FCA5A5', cursor: 'pointer', fontSize: '18px' }}
          >
            ✕
          </button>
        </div>
      )}

      {successMessage && (
        <div style={{ 
          background: 'rgba(34, 197, 94, 0.1)',
          border: '1px solid rgba(34, 197, 94, 0.3)',
          borderRadius: '6px',
          padding: '12px 16px',
          marginBottom: '16px',
          color: '#86EFAC',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center'
        }}>
          <span>✓ {successMessage}</span>
          <button 
            onClick={() => setSuccessMessage(null)}
            style={{ background: 'none', border: 'none', color: '#86EFAC', cursor: 'pointer', fontSize: '18px' }}
          >
            ✕
          </button>
        </div>
      )}

      {msg && (
        <div className={msg.includes("Hiba") ? "alert alert-error" : "alert alert-success"}>
          {msg}
        </div>
      )}

      {loading ? (
        <div style={{ textAlign: 'center', padding: '40px' }}>
          <div className="spinner"></div>
          <p style={{ color: '#94A3B8', marginTop: '20px' }}>Adatok betöltése...</p>
        </div>
      ) : (
        <form onSubmit={submit}>
          <div>
            <label>Válassz járművet</label>
            <select 
              value={car_id}
              onChange={(e) => setCarId(e.target.value)}
              style={{ width: '100%', borderColor: validationErrors.car_id ? '#EF4444' : undefined }}
            >
              <option value="">-- Válassz autót --</option>
              {cars.map(c => (
                <option key={c.id} value={c.id}>
                  {c.make_model} {c.year ? `(${c.year})` : ''}
                </option>
              ))}
            </select>
            {validationErrors.car_id && (
              <p style={{ color: '#EF4444', fontSize: '0.75rem', margin: '4px 0 0 0' }}>
                ✕ {validationErrors.car_id}
              </p>
            )}
          </div>

          <div>
            <label>Dátum</label>
            <input 
              type="date" 
              value={date}
              onChange={(e) => setDate(e.target.value)}
              min={new Date().toISOString().split('T')[0]}
              style={{ borderColor: validationErrors.date ? '#EF4444' : undefined }}
            />
            {validationErrors.date && (
              <p style={{ color: '#EF4444', fontSize: '0.75rem', margin: '4px 0 0 0' }}>
                ✕ {validationErrors.date}
              </p>
            )}
          </div>

          <div>
            <label>Időpont</label>
            <input 
              type="time" 
              value={time}
              onChange={(e) => setTime(e.target.value)}
              style={{ borderColor: validationErrors.time ? '#EF4444' : undefined }}
            />
            {validationErrors.time && (
              <p style={{ color: '#EF4444', fontSize: '0.75rem', margin: '4px 0 0 0' }}>
                ✕ {validationErrors.time}
              </p>
            )}
          </div>

          <div>
            <label>Szolgáltatás</label>
            <select 
              value={service}
              onChange={(e) => setService(e.target.value)}
              style={{ width: '100%', borderColor: validationErrors.service ? '#EF4444' : undefined }}
            >
              <option value="">-- Válassz szolgáltatást --</option>
              <option value="Olajcsere">Olajcsere</option>
              <option value="Fék ellenőrzés">Fék ellenőrzés</option>
              <option value="Gumiabroncs csere">Gumiabroncs csere</option>
              <option value="Diagnosztika">Diagnosztika</option>
              <option value="Általános szerviz">Általános szerviz</option>
              <option value="Szellőztetőfolyadék csere">Szellőztetőfolyadék csere</option>
              <option value="Légszűrő csere">Légszűrő csere</option>
              <option value="Gyújt gyertya csere">Gyújt gyertya csere</option>
              <option value="Féktöltés csere">Féktöltés csere</option>
              <option value="Fékfolyadék csere">Fékfolyadék csere</option>
              <option value="Sebességváltó szerviz">Sebességváltó szerviz</option>
              <option value="Akkumulátor csere">Akkumulátor csere</option>
              <option value="Alternátor vizsgálat">Alternátor vizsgálat</option>
              <option value="Indítómotor vizsgálat">Indítómotor vizsgálat</option>
              <option value="Gumiabroncs forgat">Gumiabroncs forgat</option>
              <option value="Szögbeállítás">Szögbeállítás</option>
              <option value="Felfüggesztés vizsgálat">Felfüggesztés vizsgálat</option>
              <option value="Kipufogó vizsgálat">Kipufogó vizsgálat</option>
              <option value="Légkondicionálás szerviz">Légkondicionálás szerviz</option>
              <option value="Ablaktörlő csere">Ablaktörlő csere</option>
              <option value="Fékmester vizsgálat">Fékmester vizsgálat</option>
              <option value="Biztosíték csere">Biztosíték csere</option>
            </select>
            {validationErrors.service && (
              <p style={{ color: '#EF4444', fontSize: '0.75rem', margin: '4px 0 0 0' }}>
                ✕ {validationErrors.service}
              </p>
            )}
          </div>

          <button type="submit" style={{ marginTop: '10px' }} disabled={submitting}>
            {submitting ? 'Foglalás leadása...' : 'Foglalás leadása'}
          </button>
        </form>
      )}
    </div>
  );
}