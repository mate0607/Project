import React from "react";
import { Routes, Route, Link, useNavigate, Navigate } from "react-router-dom";
import { getToken, clearToken, setToken } from "./api.js";
import LocationsDashboard from "./pages/locations/LocationsDashboard.jsx";
import LocationDetail from "./pages/locations/LocationDetail.jsx";
import "./App.css";

function LocationsNav() {
  const nav = useNavigate();
  const token = getToken();

  return (
    <nav style={{ 
      background: 'rgba(30, 41, 59, 0.8)',
      backdropFilter: 'blur(10px)',
      padding: '18px 30px',
      boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.3)',
      borderBottom: '1px solid rgba(148, 163, 184, 0.15)',
      marginBottom: '30px'
    }}>
      <div style={{ 
        maxWidth: '1200px',
        margin: '0 auto',
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center'
      }}>
        <Link to="/locations" style={{ 
          color: '#fff',
          textDecoration: 'none',
          fontWeight: 700,
          fontSize: '1.5rem',
          background: 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
          WebkitBackgroundClip: 'text',
          WebkitTextFillColor: 'transparent',
          backgroundClip: 'text'
        }}>
          AutoNex Lokációk
        </Link>
        <div style={{ display: 'flex', gap: 16, alignItems: 'center' }}>
          <Link to="/locations/dashboard" style={{ 
            color: '#CBD5E1',
            textDecoration: 'none',
            fontWeight: 500,
            padding: '8px 14px',
            borderRadius: '6px',
            transition: 'all 0.2s'
          }}>
            Szervizpontok
          </Link>
        </div>
      </div>
    </nav>
  );
}

function LocationsHome() {
  return (
    <div style={{ maxWidth: '1000px', margin: '60px auto', padding: '0 20px' }}>
      <div className="card" style={{ textAlign: 'center', padding: '60px 40px' }}>
        <h1 style={{ marginBottom: '20px', fontSize: '2.5rem' }}>
          AutoNex Szervizpontok
        </h1>
        <p style={{ 
          fontSize: '1.125rem',
          color: '#94A3B8',
          marginBottom: '30px',
          lineHeight: '1.8',
          maxWidth: '600px',
          margin: '0 auto 30px'
        }}>
          Kezelje szervizpontjait, tekintse meg az ügyfél foglalásokat és nyomon követheti a szervízpontok teljesítményét.
        </p>
        <div style={{ display: 'flex', gap: '16px', justifyContent: 'center', flexWrap: 'wrap' }}>
          <Link to="/locations/dashboard">
            <button style={{ 
              padding: '14px 32px',
              fontSize: '16px'
            }}>
              Szervizpontok megtekintése
            </button>
          </Link>
          <a href="/" style={{ textDecoration: 'none' }}>
            <button className="secondary" style={{ 
              padding: '14px 32px',
              fontSize: '16px'
            }}>
              Vissza az ügyféloldalra
            </button>
          </a>
        </div>
      </div>
    </div>
  );
}

function PrivateRoute({ children }) {
  return children;
}

export default function LocationsApp() {
  return (
    <>
      <LocationsNav />
      <Routes>
        <Route path="/" element={<LocationsHome />} />
        
        <Route path="/dashboard" element={<PrivateRoute><LocationsDashboard /></PrivateRoute>} />
        <Route path="/location/:locationId" element={<PrivateRoute><LocationDetail /></PrivateRoute>} />
      </Routes>
    </>
  );
}
