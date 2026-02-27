import { useState, useEffect } from "react";
import { api } from "../api.js";
import { Link } from "react-router-dom";
import jsPDF from "jspdf";

export default function Booking() {
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [editingId, setEditingId] = useState(null);
  const [editForm, setEditForm] = useState({ date: "", time: "", service: "" });
  const [error, setError] = useState(null);
  const [validationErrors, setValidationErrors] = useState({});
  const [successMessage, setSuccessMessage] = useState(null);

  const loadAppointments = () => {
    api.getMyAppointments()
      .then(res => {
        setAppointments(Array.isArray(res) ? res : []);
        setLoading(false);
        setError(null);
      })
      .catch(err => {
        console.error('Hiba a foglalások betöltése során:', err);
        setError('Nem sikerült a foglalások betöltése. Kérjük, próbálja meg később.');
        setAppointments([]);
        setLoading(false);
      });
  };

  useEffect(() => {
    loadAppointments();
  }, []);

  // Auto-update a foglalások státusza 4 óra elteltével (elvileg)
  useEffect(() => {
    const checkAndUpdateAppointments = async () => {
      const now = new Date();
      
      for (const apt of appointments) {

        if (apt.status === 'completed') continue;
        

        const aptDate = new Date(apt.date);
        const [hours, minutes] = apt.time.split(':');
        aptDate.setHours(parseInt(hours), parseInt(minutes), 0);
        

        const fourHoursLater = new Date(aptDate.getTime() + 4 * 60 * 60 * 1000);
        
        if (now >= fourHoursLater) {
          try {
            await api.updateAppointment(apt.id, apt.date, apt.time, apt.service, 'completed');
            loadAppointments();
            break;
          } catch (err) {
            console.error('Failed to auto-update appointment:', err);
          }
        }
      }
    };

    checkAndUpdateAppointments();
    const interval = setInterval(checkAndUpdateAppointments, 60000);
    
    return () => clearInterval(interval);
  }, [appointments]);

  const handleDelete = async (id) => {
    if (!window.confirm("Biztosan törölni szeretnéd ezt a foglalást?")) return;
    
    try {
      setError(null);
      await api.deleteAppointment(id);
      setSuccessMessage("Foglalás sikeresen törölve!");
      setTimeout(() => setSuccessMessage(null), 3000);
      loadAppointments();
    } catch (err) {
      console.error('Törlési hiba:', err);
      const errorMsg = err.response?.data?.message || err.message || 'Hiba a foglalás törlése során. Kérjük, próbálja meg később.';
      setError(errorMsg);
    }
  };

  const startEdit = (apt) => {
    setEditingId(apt.id);
    setEditForm({ date: apt.date, time: apt.time, service: apt.service });
  };

  const cancelEdit = () => {
    setEditingId(null);
    setEditForm({ date: "", time: "", service: "" });
    setValidationErrors({});
    setError(null);
  };

  const validateForm = () => {
    const errors = {};
    
    if (!editForm.date) {
      errors.date = 'A dátum megadása kötelező';
    } else {
      const selectedDate = new Date(editForm.date);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      if (selectedDate < today) {
        errors.date = 'A dátum nem lehet a múltban';
      }
    }
    
    if (!editForm.time) {
      errors.time = 'Az idő megadása kötelező';
    } else {
      const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
      if (!timeRegex.test(editForm.time)) {
        errors.time = 'Érvénytelen időformátum (HH:MM)';
      }
    }
    
    if (!editForm.service) {
      errors.service = 'A szolgáltatás kiválasztása kötelező';
    }
    
    setValidationErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const handleUpdate = async (id) => {
    if (!validateForm()) {
      setError('Kérjük, javítsa ki a hibákat az űrlapban');
      return;
    }
    
    try {
      setError(null);
      await api.updateAppointment(id, editForm.date, editForm.time, editForm.service);
      setEditingId(null);
      setValidationErrors({});
      setSuccessMessage("Foglalás sikeresen frissítve!");
      setTimeout(() => setSuccessMessage(null), 3000);
      loadAppointments();
    } catch (err) {
      console.error('Frissítési hiba:', err);
      const errorMsg = err.response?.data?.message || err.message || 'Hiba a foglalás frissítése során. Kérjük, próbálja meg később.';
      setError(errorMsg);
    }
  };

  const exportToPDF = () => {
    try {
      if (appointments.length === 0) {
        setError('Nincsenek foglalások az exportáláshoz');
        return;
      }
      
      const doc = new jsPDF();
      let yPosition = 20;
      
      doc.setFontSize(16);
      doc.text("Foglalások Kivonat", 20, yPosition);
      yPosition += 15;
      
      doc.setFontSize(10);
      doc.text(`Dátum: ${new Date().toLocaleDateString('hu-HU')}`, 20, yPosition);
      yPosition += 10;
      
      if (appointments.length === 0) {
        doc.text("Nincs mentett foglalás.", 20, yPosition);
      } else {
        doc.setFontSize(11);
        appointments.forEach((apt, index) => {
          const dateObj = new Date(apt.date);
          const formattedDate = dateObj.toLocaleDateString('hu-HU', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
          });
          
          const statusText = apt.status === 'completed' ? 'Kész' : apt.status === 'confirmed' ? 'Megerősített' : 'Függőben';
          
          // Add appointment details
          doc.text(`${index + 1}. ${apt.service}`, 20, yPosition);
          yPosition += 6;
          
          doc.setFontSize(9);
          if (apt.car_name) {
            doc.text(`Autó: ${apt.car_name}`, 25, yPosition);
            yPosition += 5;
          }
          doc.text(`Dátum: ${formattedDate} | Idő: ${apt.time}`, 25, yPosition);
          yPosition += 5;
          doc.text(`Státusz: ${statusText}`, 25, yPosition);
          yPosition += 8;
          
          doc.setFontSize(11);
          
          // Check if we need a new page
          if (yPosition > 270) {
            doc.addPage();
            yPosition = 20;
          }
        });
      }
      
      doc.save(`foglalasok_${new Date().getTime()}.pdf`);
      setError(null);
      setSuccessMessage("PDF sikeresen exportálva!");
      setTimeout(() => setSuccessMessage(null), 3000);
    } catch (err) {
      console.error('PDF exportálási hiba:', err);
      setError('Hiba a PDF exportálása során. Kérjük, próbálja meg később.');
    }
  };

  if (loading) {
    return (
      <div style={{ maxWidth: '1000px', margin: '30px auto', padding: '0 20px' }}>
        <h2>Foglalások</h2>
        <div style={{ textAlign: 'center', padding: '60px 40px' }}>
          <div className="spinner"></div>
          <p style={{ color: '#94A3B8', marginTop: '20px' }}>Foglalások betöltése...</p>
        </div>
      </div>
    );
  }

  return (
    <div style={{ maxWidth: '1000px', margin: '30px auto', padding: '0 20px' }}>
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
      
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
        <h2 style={{ margin: 0 }}>Foglalások</h2>
        <div style={{ display: 'flex', gap: '10px' }}>
          <button 
            onClick={exportToPDF}
            style={{ padding: '10px 20px', fontSize: '14px', background: 'rgba(34, 197, 94, 0.2)', border: '1px solid rgba(34, 197, 94, 0.3)', color: '#22C55E' }}
          >
            📥 PDF-be exportál
          </button>
          <Link to="/book-appointment">
            <button style={{ padding: '10px 20px', fontSize: '14px' }}>
              + Új foglalás
            </button>
          </Link>
        </div>
      </div>
      
      {appointments.length === 0 ? (
        <div className="card" style={{ textAlign: 'center', padding: '40px' }}>
          <p style={{ color: '#94A3B8' }}>Nincs mentett foglalás.</p>
        </div>
      ) : (
        <div style={{ display: 'grid', gap: '12px' }}>
          {appointments.map(apt => {

            const dateObj = new Date(apt.date);
            const formattedDate = dateObj.toLocaleDateString('hu-HU', {
              year: 'numeric',
              month: '2-digit',
              day: '2-digit'
            });
            
            return (
              <div key={apt.id} className="card">
                {editingId === apt.id ? (
                  <div>
                    <h3 style={{ margin: '0 0 16px 0', fontSize: '1.125rem' }}>Foglalás szerkesztése</h3>
                    <div style={{ display: 'grid', gap: '12px', marginBottom: '16px' }}>
                      <div>
                        <label style={{ display: 'block', color: '#94A3B8', fontSize: '0.875rem', marginBottom: '4px' }}>Dátum</label>
                        <input 
                          type="date"
                          value={editForm.date}
                          onChange={(e) => setEditForm({...editForm, date: e.target.value})}
                          min={new Date().toISOString().split('T')[0]}
                          style={{ width: '100%', borderColor: validationErrors.date ? '#EF4444' : undefined }}
                        />
                        {validationErrors.date && (
                          <p style={{ color: '#EF4444', fontSize: '0.75rem', margin: '4px 0 0 0' }}>
                            ✕ {validationErrors.date}
                          </p>
                        )}
                      </div>
                      <div>
                        <label style={{ display: 'block', color: '#94A3B8', fontSize: '0.875rem', marginBottom: '4px' }}>Idő</label>
                        <input 
                          type="time"
                          value={editForm.time}
                          onChange={(e) => setEditForm({...editForm, time: e.target.value})}
                          style={{ width: '100%', borderColor: validationErrors.time ? '#EF4444' : undefined }}
                        />
                        {validationErrors.time && (
                          <p style={{ color: '#EF4444', fontSize: '0.75rem', margin: '4px 0 0 0' }}>
                            ✕ {validationErrors.time}
                          </p>
                        )}
                      </div>
                      <div>
                        <label style={{ display: 'block', color: '#94A3B8', fontSize: '0.875rem', marginBottom: '4px' }}>Szolgáltatás</label>
                        <select 
                          value={editForm.service}
                          onChange={(e) => setEditForm({...editForm, service: e.target.value})}
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
                    </div>
                    <div style={{ display: 'flex', gap: '8px' }}>
                      <button onClick={() => handleUpdate(apt.id)} style={{ padding: '8px 16px', fontSize: '14px' }}>
                        Mentés
                      </button>
                      <button onClick={cancelEdit} className="secondary" style={{ padding: '8px 16px', fontSize: '14px' }}>
                        Mégse
                      </button>
                    </div>
                  </div>
                ) : (
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: '16px' }}>
                    <div style={{ flex: 1 }}>
                      <h3 style={{ margin: '0 0 8px 0', fontSize: '1.125rem' }}>
                        {apt.service}
                      </h3>
                      {apt.car_name && (
                        <p style={{ color: '#94A3B8', margin: '0 0 8px 0', fontSize: '0.875rem' }}>
                          Autó: <strong style={{ color: '#F1F5F9' }}>{apt.car_name}</strong>
                        </p>
                      )}
                      <p style={{ color: '#94A3B8', margin: '0 0 8px 0' }}>
                        Dátum: <strong style={{ color: '#F1F5F9' }}>{formattedDate}</strong> • Idő: <strong style={{ color: '#F1F5F9' }}>{apt.time}</strong>
                      </p>
                      {apt.user_name && (
                        <p style={{ color: '#64748B', margin: '0', fontSize: '0.8rem' }}>
                          Foglalta: {apt.user_name}
                        </p>
                      )}
                    </div>
                    <div style={{ textAlign: 'right', display: 'flex', flexDirection: 'column', gap: '8px', alignItems: 'flex-end' }}>
                      <span className={`badge badge-${apt.status === 'completed' ? 'success' : apt.status === 'confirmed' ? 'info' : 'warning'}`}>
                        {apt.status === 'completed' ? 'Kész' : apt.status === 'confirmed' ? 'Megerősített' : 'Függőben'}
                      </span>
                      <div style={{ display: 'flex', gap: '8px' }}>
                        <button 
                          onClick={() => startEdit(apt)}
                          style={{ 
                            padding: '6px 12px', 
                            fontSize: '13px',
                            background: 'rgba(129, 140, 248, 0.2)',
                            border: '1px solid rgba(129, 140, 248, 0.3)',
                            color: '#818CF8'
                          }}
                        >
                          Szerkeszt
                        </button>
                        <button 
                          onClick={() => handleDelete(apt.id)}
                          style={{ 
                            padding: '6px 12px', 
                            fontSize: '13px',
                            background: 'rgba(239, 68, 68, 0.2)',
                            border: '1px solid rgba(239, 68, 68, 0.3)',
                            color: '#EF4444'
                          }}
                        >
                          Törlés
                        </button>
                      </div>
                    </div>
                  </div>
                )}
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
}
