import React from "react";
import { Routes, Route, Link } from "react-router-dom";
import ServiceDashboard from "./pages/service/ServiceDashboard.jsx";
import "./App.css";

function ServiceNav() {
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
        <Link to="/service" style={{ 
          color: '#fff',
          textDecoration: 'none',
          fontWeight: 700,
          fontSize: '1.5rem',
          background: 'linear-gradient(135deg, #10B981 0%, #059669 100%)',
          WebkitBackgroundClip: 'text',
          WebkitTextFillColor: 'transparent',
          backgroundClip: 'text'
        }}>
          AutoNex Szerviz
        </Link>
        <div style={{ display: 'flex', gap: 16, alignItems: 'center' }}>
          <Link to="/service/dashboard" style={{ 
            color: '#CBD5E1',
            textDecoration: 'none',
            fontWeight: 500,
            padding: '8px 14px',
            borderRadius: '6px',
            transition: 'all 0.2s'
          }}>
            Foglalások
          </Link>
          <a href="/" style={{ 
            color: '#94A3B8',
            textDecoration: 'none',
            fontWeight: 500,
            padding: '8px 14px',
            borderRadius: '6px',
            transition: 'all 0.2s'
          }}>
            Vissza az ügyféloldalra
          </a>
        </div>
      </div>
    </nav>
  );
}

export default function ServiceApp() {
  return (
    <>
      <ServiceNav />
      <Routes>
        <Route path="/dashboard" element={<ServiceDashboard />} />
        <Route path="/" element={<ServiceDashboard />} />
      </Routes>
    </>
  );
}
