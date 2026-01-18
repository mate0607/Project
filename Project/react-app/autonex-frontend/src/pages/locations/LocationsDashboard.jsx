import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";

export default function LocationsDashboard() {
  const [locations, setLocations] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Simulate fetching locations from backend
    const mockLocations = [
      {
        id: 1,
        name: "AutoNex Budapest - Belváros",
        address: "1051 Budapest, Deák Ferenc utca 12.",
        phone: "+36-1-123-4567",
        email: "budapest-belvaros@autonex.hu",
        manager: "Kiss László",
        active_appointments: 5,
        completed_appointments: 24,
        total_revenue: 542000,
        rating: 4.8,
        hours: "Hétfő-Péntek: 08:00-18:00, Szombat: 09:00-14:00"
      },
      {
        id: 2,
        name: "AutoNex Debrecen",
        address: "4025 Debrecen, Nagy Alfréd utca 5.",
        phone: "+36-52-123-4567",
        email: "debrecen@autonex.hu",
        manager: "Szabó Péter",
        active_appointments: 3,
        completed_appointments: 18,
        total_revenue: 420000,
        rating: 4.6,
        hours: "Hétfő-Péntek: 08:00-17:00, Szombat: 09:00-13:00"
      },
      {
        id: 3,
        name: "AutoNex Szeged",
        address: "6722 Szeged, Klauzál tér 8.",
        phone: "+36-62-123-4567",
        email: "szeged@autonex.hu",
        manager: "Tóth Ágnes",
        active_appointments: 2,
        completed_appointments: 15,
        total_revenue: 380000,
        rating: 4.7,
        hours: "Hétfő-Péntek: 08:00-17:00, Szombat: 09:00-13:00"
      }
    ];

    setTimeout(() => {
      setLocations(mockLocations);
      setLoading(false);
    }, 500);
  }, []);

  if (loading) {
    return (
      <div style={{ maxWidth: '1000px', margin: '30px auto', padding: '0 20px' }}>
        <div style={{ textAlign: 'center', padding: '60px 40px' }}>
          <div className="spinner"></div>
          <p style={{ color: '#94A3B8', marginTop: '20px' }}>Szervizpontok betöltése...</p>
        </div>
      </div>
    );
  }

  return (
    <div style={{ maxWidth: '1200px', margin: '30px auto', padding: '0 20px' }}>
      <div style={{ marginBottom: 30, display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
        <div>
          <h2 style={{ marginBottom: '20px' }}>Szervizpontok</h2>
          <p style={{ color: '#94A3B8' }}>
            Összesen: <strong style={{ color: '#F1F5F9' }}>{locations.length}</strong> szervizpont
          </p>
        </div>
        <a href="/" style={{ textDecoration: 'none' }}>
          <button style={{ padding: '8px 16px', fontSize: '14px' }}>
            Vissza az ügyféloldalra
          </button>
        </a>
      </div>

      <div style={{ display: 'grid', gap: '20px' }}>
        {locations.map(loc => (
          <div key={loc.id} className="card">
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: '20px' }}>
              <div style={{ flex: 1 }}>
                <h3 style={{ marginBottom: '8px', fontSize: '1.25rem' }}>
                  {loc.name}
                </h3>
                <p style={{ color: '#94A3B8', fontSize: '0.875rem', margin: '0 0 16px 0' }}>
                  Vezetője: {loc.manager}
                </p>

                <div style={{ 
                  display: 'grid',
                  gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
                  gap: '16px',
                  marginBottom: '16px'
                }}>
                  <div>
                    <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: '0 0 4px 0', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                      Cím
                    </p>
                    <p style={{ color: '#F1F5F9', margin: 0, fontSize: '0.9rem' }}>
                      {loc.address}
                    </p>
                  </div>
                  <div>
                    <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: '0 0 4px 0', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                      Telefon
                    </p>
                    <p style={{ color: '#F1F5F9', margin: 0, fontSize: '0.9rem' }}>
                      {loc.phone}
                    </p>
                  </div>
                  <div>
                    <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: '0 0 4px 0', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                      Email
                    </p>
                    <p style={{ color: '#F1F5F9', margin: 0, fontSize: '0.9rem' }}>
                      {loc.email}
                    </p>
                  </div>
                </div>

                <div style={{ 
                  display: 'grid',
                  gridTemplateColumns: 'repeat(auto-fit, minmax(150px, 1fr))',
                  gap: '12px',
                  marginBottom: '16px'
                }}>
                  <div style={{ background: 'rgba(16, 185, 129, 0.1)', padding: '12px', borderRadius: '6px', border: '1px solid rgba(16, 185, 129, 0.2)' }}>
                    <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: '0 0 4px 0' }}>Aktív foglalások</p>
                    <p style={{ color: '#10B981', margin: 0, fontWeight: '600', fontSize: '1.125rem' }}>
                      {loc.active_appointments}
                    </p>
                  </div>
                  <div style={{ background: 'rgba(129, 140, 248, 0.1)', padding: '12px', borderRadius: '6px', border: '1px solid rgba(129, 140, 248, 0.2)' }}>
                    <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: '0 0 4px 0' }}>Kész foglalások</p>
                    <p style={{ color: '#818CF8', margin: 0, fontWeight: '600', fontSize: '1.125rem' }}>
                      {loc.completed_appointments}
                    </p>
                  </div>
                </div>

                <div style={{ 
                  background: 'rgba(251, 191, 36, 0.1)',
                  padding: '12px',
                  borderRadius: '6px',
                  border: '1px solid rgba(251, 191, 36, 0.2)',
                  marginBottom: '16px'
                }}>
                  <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: '0 0 4px 0' }}>Nyitvatartás</p>
                  <p style={{ color: '#FBBF24', margin: 0, fontSize: '0.85rem' }}>
                    {loc.hours}
                  </p>
                </div>
              </div>

              <div style={{ textAlign: 'center', minWidth: '100px' }}>
                <div style={{ 
                  fontSize: '2rem',
                  fontWeight: '700',
                  color: '#F59E0B',
                  marginBottom: '8px'
                }}>
                  {loc.rating}
                </div>
                <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: 0 }}>
                  Értékelés
                </p>
                <Link to={`/locations/location/${loc.id}`} style={{ marginTop: '12px', display: 'block' }}>
                  <button style={{ padding: '8px 16px', fontSize: '13px', width: '100%' }}>
                    Részletek
                  </button>
                </Link>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
