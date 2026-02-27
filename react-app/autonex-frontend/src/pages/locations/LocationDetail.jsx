import React, { useState } from "react";
import { useParams, Link, useNavigate } from "react-router-dom";

export default function LocationDetail() {
  const { locationId } = useParams();
  const nav = useNavigate();
  const [activeTab, setActiveTab] = useState("info");

  // Mock location data
  const location = {
    id: locationId,
    name: "AutoNex Budapest - Belváros",
    address: "1051 Budapest, Deák Ferenc utca 12.",
    phone: "+36-1-123-4567",
    email: "budapest-belvaros@autonex.hu",
    manager: "Kiss László",
    manager_phone: "+36-30-123-4567",
    employees: 8,
    active_appointments: 5,
    completed_appointments: 24,
    total_revenue: 542000,
    rating: 4.8,
    hours: "Hétfő-Péntek: 08:00-18:00, Szombat: 09:00-14:00",
    services: ["Olajcsere", "Fék ellenőrzés", "Diagnosztika", "Gumiabroncs csere", "Általános szerviz"],
    equipment: ["Diagnosztikai eszköz", "Olajcsere berendezés", "Fékellenőrző", "Gumiabroncs szerelő"]
  };

  const recentAppointments = [
    { id: 1, client: "Nagy János", service: "Olajcsere", date: "2026-01-20", status: "pending" },
    { id: 2, client: "Kiss Mária", service: "Fék ellenőrzés", date: "2026-01-21", status: "confirmed" },
    { id: 3, client: "Szabó Péter", service: "Diagnosztika", date: "2026-01-22", status: "completed" }
  ];

  return (
    <div style={{ maxWidth: '1000px', margin: '30px auto', padding: '0 20px' }}>
      <Link to="/locations/dashboard" style={{ color: '#818CF8', textDecoration: 'none', fontSize: '0.875rem', fontWeight: '500', marginBottom: '20px', display: 'block' }}>
        Vissza
      </Link>

      <div className="card" style={{ marginBottom: '20px' }}>
        <div style={{ marginBottom: '24px' }}>
          <h2 style={{ marginBottom: '8px' }}>{location.name}</h2>
          <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
            <span style={{ color: '#94A3B8', fontSize: '0.875rem' }}>
              Vezetője: {location.manager}
            </span>
            <span style={{ 
              fontSize: '1.5rem',
              fontWeight: '700',
              color: '#F59E0B'
            }}>
              {location.rating}
            </span>
          </div>
        </div>

        <div style={{ 
          display: 'flex',
          gap: '12px',
          marginBottom: '24px',
          borderBottom: '1px solid rgba(148, 163, 184, 0.2)',
          paddingBottom: '16px'
        }}>
          {['info', 'appointments', 'serviceTimes', 'team'].map(tab => (
            <button
              key={tab}
              onClick={() => setActiveTab(tab)}
              style={{
                background: activeTab === tab ? 'rgba(129, 140, 248, 0.2)' : 'transparent',
                border: `1px solid ${activeTab === tab ? 'rgba(129, 140, 248, 0.3)' : 'transparent'}`,
                color: activeTab === tab ? '#818CF8' : '#94A3B8',
                padding: '8px 16px',
                borderRadius: '6px',
                cursor: 'pointer',
                fontWeight: '500',
                transition: 'all 0.2s'
              }}
            >
              {tab === 'info' ? 'Információ' : tab === 'appointments' ? 'Foglalások' : tab === 'serviceTimes' ? 'Szolgáltatás Idők' : 'Csapat'}
            </button>
          ))}
        </div>

        {activeTab === 'info' && (
          <div>
            <div style={{ marginBottom: '24px' }}>
              <h3 style={{ fontSize: '1rem', marginBottom: '16px', color: '#CBD5E1' }}>Elérhetőség</h3>
              <div style={{ display: 'grid', gap: '12px' }}>
                <div style={{ display: 'flex', gap: '12px' }}>
                  <span style={{ color: '#94A3B8', minWidth: '100px' }}>Cím:</span>
                  <span style={{ color: '#F1F5F9' }}>{location.address}</span>
                </div>
                <div style={{ display: 'flex', gap: '12px' }}>
                  <span style={{ color: '#94A3B8', minWidth: '100px' }}>Telefon:</span>
                  <span style={{ color: '#F1F5F9' }}>{location.phone}</span>
                </div>
                <div style={{ display: 'flex', gap: '12px' }}>
                  <span style={{ color: '#94A3B8', minWidth: '100px' }}>Email:</span>
                  <span style={{ color: '#F1F5F9' }}>{location.email}</span>
                </div>
                <div style={{ display: 'flex', gap: '12px' }}>
                  <span style={{ color: '#94A3B8', minWidth: '100px' }}>Nyitvatartás:</span>
                  <span style={{ color: '#F1F5F9' }}>{location.hours}</span>
                </div>
              </div>
            </div>

            <div style={{ marginBottom: '24px' }}>
              <h3 style={{ fontSize: '1rem', marginBottom: '16px', color: '#CBD5E1' }}>Vezetője</h3>
              <div style={{ display: 'grid', gap: '12px' }}>
                <div style={{ display: 'flex', gap: '12px' }}>
                  <span style={{ color: '#94A3B8', minWidth: '100px' }}>Név:</span>
                  <span style={{ color: '#F1F5F9' }}>{location.manager}</span>
                </div>
                <div style={{ display: 'flex', gap: '12px' }}>
                  <span style={{ color: '#94A3B8', minWidth: '100px' }}>Telefon:</span>
                  <span style={{ color: '#F1F5F9' }}>{location.manager_phone}</span>
                </div>
              </div>
            </div>

            <div style={{ marginBottom: '24px' }}>
              <h3 style={{ fontSize: '1rem', marginBottom: '16px', color: '#CBD5E1' }}>Szolgáltatások</h3>
              <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
                {location.services.map((service, idx) => (
                  <span key={idx} className="badge badge-info" style={{ textTransform: 'none' }}>
                    {service}
                  </span>
                ))}
              </div>
            </div>

            <div>
              <h3 style={{ fontSize: '1rem', marginBottom: '16px', color: '#CBD5E1' }}>Felszerelés</h3>
              <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
                {location.equipment.map((equip, idx) => (
                  <span key={idx} className="badge badge-warning" style={{ textTransform: 'none' }}>
                    {equip}
                  </span>
                ))}
              </div>
            </div>
          </div>
        )}

        {activeTab === 'appointments' && (
          <div>
            <h3 style={{ fontSize: '1rem', marginBottom: '16px', color: '#CBD5E1' }}>Legutóbbi foglalások</h3>
            <div style={{ display: 'grid', gap: '12px' }}>
              {recentAppointments.map(apt => (
                <div key={apt.id} style={{ 
                  background: 'rgba(129, 140, 248, 0.1)',
                  padding: '16px',
                  borderRadius: '8px',
                  border: '1px solid rgba(129, 140, 248, 0.2)',
                  display: 'flex',
                  justifyContent: 'space-between',
                  alignItems: 'center'
                }}>
                  <div>
                    <p style={{ color: '#F1F5F9', fontWeight: '600', margin: '0 0 4px 0' }}>
                      {apt.client} - {apt.service}
                    </p>
                    <p style={{ color: '#94A3B8', fontSize: '0.85rem', margin: 0 }}>
                      {apt.date}
                    </p>
                  </div>
                  <span className={`badge badge-${apt.status === 'completed' ? 'success' : apt.status === 'confirmed' ? 'info' : 'warning'}`}>
                    {apt.status === 'completed' ? 'Kész' : apt.status === 'confirmed' ? 'Megerősített' : 'Függőben'}
                  </span>
                </div>
              ))}
            </div>
          </div>
        )}

        {activeTab === 'serviceTimes' && (
          <div>
            <h3 style={{ fontSize: '1rem', marginBottom: '16px', color: '#CBD5E1' }}>Szolgáltatás Időtartamok</h3>
            <div style={{ display: 'grid', gap: '12px' }}>
              {location.services.map((service, idx) => (
                <div key={idx} style={{ 
                  background: 'rgba(79, 70, 229, 0.1)',
                  padding: '16px',
                  borderRadius: '8px',
                  border: '1px solid rgba(79, 70, 229, 0.2)',
                  display: 'flex',
                  justifyContent: 'space-between',
                  alignItems: 'center'
                }}>
                  <div>
                    <p style={{ color: '#F1F5F9', fontWeight: '600', margin: '0 0 4px 0' }}>
                      {service}
                    </p>
                  </div>
                  <div style={{ textAlign: 'right' }}>
                    <span style={{ 
                      background: 'rgba(129, 140, 248, 0.3)',
                      padding: '6px 12px',
                      borderRadius: '6px',
                      color: '#818CF8',
                      fontWeight: '600',
                      fontSize: '0.875rem'
                    }}>
                      {idx === 0 ? '30 perc' : idx === 1 ? '45 perc' : idx === 2 ? '60 perc' : idx === 3 ? '40 perc' : '90 perc'}
                    </span>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {activeTab === 'team' && (
          <div>
            <h3 style={{ fontSize: '1rem', marginBottom: '16px', color: '#CBD5E1' }}>Csapattagok</h3>
            <div style={{ 
              background: 'rgba(16, 185, 129, 0.1)',
              padding: '20px',
              borderRadius: '8px',
              border: '1px solid rgba(16, 185, 129, 0.2)',
              textAlign: 'center'
            }}>
              <p style={{ color: '#F1F5F9', fontWeight: '600', fontSize: '1.25rem', margin: '0 0 8px 0' }}>
                {location.employees}
              </p>
              <p style={{ color: '#94A3B8', margin: 0 }}>
                Munkatárs az ezen a helyszínen
              </p>
            </div>
          </div>
        )}
      </div>

      <div style={{ marginTop: '30px' }}>
        <Link to="/locations/dashboard">
          <button style={{ padding: '12px 24px' }}>Vissza a szervizpontokhoz</button>
        </Link>
      </div>
    </div>
  );
}
