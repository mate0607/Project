import React from "react";
import { Routes, Route, Link, useNavigate, Navigate } from "react-router-dom";
import { api, setToken, clearToken, getToken } from "./api.js";
import Register from "./pages/Register.jsx";
import Login from "./pages/Login.jsx";
import Dashboard from "./pages/Dashboard.jsx";
import NewCar from "./pages/NewCar.jsx";
import CarDetail from "./pages/CarDetail.jsx";
import Recommendation from "./pages/Recommendation.jsx";
import BookAppointment from "./pages/BookAppointment.jsx";
import Booking from "./pages/Booking.jsx";
import UserProfile from "./pages/UserProfile.jsx";
import KnowledgeHub from "./pages/KnowledgeHub.jsx";
import ForSale from "./pages/ForSale.jsx";
import SaleDetail from "./pages/SaleDetail.jsx";
import Footer from "./components/Footer.jsx";
import "./App.css";


function Nav() {
  const nav = useNavigate();
  const token = getToken();

  return (
    <nav style={{ 
      background: 'linear-gradient(90deg, #1E293B 0%, #0F172A 50%, #1a1a3e 100%)',
      backdropFilter: 'blur(20px)',
      padding: '16px 40px',
      boxShadow: '0 10px 40px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.05)',
      borderBottom: '1px solid rgba(99, 102, 241, 0.25)',
      marginBottom: '40px',
      position: 'sticky',
      top: 0,
      zIndex: 999
    }}>
      <div style={{ 
        maxWidth: '1400px',
        margin: '0 auto',
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        gap: '40px'
      }}>
        <Link to="/" style={{ 
          color: '#fff',
          textDecoration: 'none',
          fontWeight: 800,
          fontSize: '1.6rem',
          letterSpacing: '-1px',
          background: 'linear-gradient(135deg, #818CF8 0%, #EC4899 100%)',
          WebkitBackgroundClip: 'text',
          WebkitTextFillColor: 'transparent',
          backgroundClip: 'text',
          flexShrink: 0
        }}>
          AutoNex
        </Link>
        <div style={{ 
          display: 'flex', 
          gap: '8px', 
          alignItems: 'center',
          flex: 1,
          justifyContent: 'flex-end'
        }}>
          {token ? (
            <>
              <NavLink to="/dashboard">Dashboard</NavLink>
              <NavLink to="/for-sale">Eladó Autók</NavLink>
              <NavLink to="/book">Időpont</NavLink>
              <NavLink to="/knowledge-hub">Tudásbázis</NavLink>
              <NavLink to="/profile">Profil</NavLink>
              <NavLink to="/locations" special>Szervizpontok</NavLink>
              <button
                onClick={() => { clearToken(); nav("/login"); }}
                style={{ 
                  padding: '10px 18px',
                  fontSize: '14px',
                  fontWeight: 600,
                  background: 'rgba(239, 68, 68, 0.2)',
                  border: '1.5px solid rgba(239, 68, 68, 0.5)',
                  color: '#FF6B6B',
                  borderRadius: '8px',
                  cursor: 'pointer',
                  transition: 'all 0.25s ease',
                  marginLeft: '8px'
                }}
                onMouseEnter={(e) => {
                  e.target.style.background = 'rgba(239, 68, 68, 0.3)';
                  e.target.style.boxShadow = '0 4px 12px rgba(239, 68, 68, 0.25)';
                }}
                onMouseLeave={(e) => {
                  e.target.style.background = 'rgba(239, 68, 68, 0.2)';
                  e.target.style.boxShadow = 'none';
                }}
              >
                Kilépés
              </button>
            </>
          ) : (
            <>
              <NavLink to="/login">Belépés</NavLink>
              <button 
                onClick={() => nav("/register")}
                style={{ 
                  padding: '10px 20px', 
                  fontSize: '14px',
                  fontWeight: 700,
                  background: 'linear-gradient(135deg, #818CF8 0%, #6366F1 100%)',
                  border: 'none',
                  color: '#fff',
                  borderRadius: '8px',
                  cursor: 'pointer',
                  transition: 'all 0.25s ease',
                  boxShadow: '0 4px 15px rgba(129, 140, 248, 0.25)',
                  marginLeft: '8px'
                }}
                onMouseEnter={(e) => {
                  e.target.style.transform = 'translateY(-2px)';
                  e.target.style.boxShadow = '0 6px 20px rgba(129, 140, 248, 0.4)';
                }}
                onMouseLeave={(e) => {
                  e.target.style.transform = 'translateY(0)';
                  e.target.style.boxShadow = '0 4px 15px rgba(129, 140, 248, 0.25)';
                }}
              >
                Regisztráció
              </button>
            </>
          )}
        </div>
      </div>
    </nav>
  );
}

function NavLink({ to, children, special }) {
  const location = window.location.pathname;
  const isActive = location === to;
  
  return (
    <Link
      to={to}
      style={{
        padding: '10px 16px',
        textDecoration: 'none',
        fontWeight: special ? 600 : 500,
        fontSize: '14px',
        color: special ? '#FBBF24' : (isActive ? '#818CF8' : '#CBD5E1'),
        borderRadius: '8px',
        background: isActive ? 'rgba(129, 140, 248, 0.15)' : (special ? 'rgba(251, 191, 36, 0.08)' : 'transparent'),
        border: isActive ? '1px solid rgba(129, 140, 248, 0.4)' : (special ? '1px solid rgba(251, 191, 36, 0.2)' : '1px solid transparent'),
        transition: 'all 0.25s ease',
        cursor: 'pointer'
      }}
      onMouseEnter={(e) => {
        if (!isActive) {
          e.target.style.background = special ? 'rgba(251, 191, 36, 0.12)' : 'rgba(129, 140, 248, 0.1)';
          e.target.style.color = special ? '#FCD34D' : '#818CF8';
        }
      }}
      onMouseLeave={(e) => {
        if (!isActive) {
          e.target.style.background = special ? 'rgba(251, 191, 36, 0.08)' : 'transparent';
          e.target.style.color = special ? '#FBBF24' : '#CBD5E1';
        }
      }}
    >
      {children}
    </Link>
  );
}

function Home() {
  return (
    <div style={{ maxWidth: '1000px', margin: '60px auto', padding: '0 20px' }}>
      <div className="card" style={{ textAlign: 'center', padding: '60px 40px' }}>
        <div style={{ 
          fontSize: '4rem',
          marginBottom: '20px'
        }}>
        </div>
        <h1 style={{ marginBottom: '20px', fontSize: '2.5rem' }}>
          AutoNex
        </h1>
        <p style={{ 
          fontSize: '1.125rem',
          color: '#94A3B8',
          marginBottom: '30px',
          lineHeight: '1.8',
          maxWidth: '600px',
          margin: '0 auto 30px'
        }}>
          Modern autó menedzsment rendszer. Regisztráld járművedet, kövesd nyomon a hibákat, 
          és kapj intelligens szerviz ajánlásokat.
        </p>
        <div style={{ 
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
          gap: '20px',
          marginTop: '40px'
        }}>
          <div style={{ 
            background: 'rgba(79, 70, 229, 0.1)',
            padding: '24px',
            borderRadius: '12px',
            border: '1px solid rgba(129, 140, 248, 0.2)'
          }}>
            <div style={{ fontSize: '2rem', marginBottom: '12px' }}></div>
            <h3 style={{ marginBottom: '8px', fontSize: '1.125rem' }}>Autó nyilvántartás</h3>
            <p style={{ color: '#94A3B8', fontSize: '0.875rem' }}>
              VIN alapú nyilvántartás és könnyű kezelés
            </p>
          </div>
          <div style={{ 
            background: 'rgba(16, 185, 129, 0.1)',
            padding: '24px',
            borderRadius: '12px',
            border: '1px solid rgba(16, 185, 129, 0.2)'
          }}>
            <div style={{ fontSize: '2rem', marginBottom: '12px' }}></div>
            <h3 style={{ marginBottom: '8px', fontSize: '1.125rem' }}>Hibák követése</h3>
            <p style={{ color: '#94A3B8', fontSize: '0.875rem' }}>
              Dokumentáld a problémákat és megoldásokat
            </p>
          </div>
          <div style={{ 
            background: 'rgba(245, 158, 11, 0.1)',
            padding: '24px',
            borderRadius: '12px',
            border: '1px solid rgba(245, 158, 11, 0.2)'
          }}>
            <div style={{ fontSize: '2rem', marginBottom: '12px' }}></div>
            <h3 style={{ marginBottom: '8px', fontSize: '1.125rem' }}>Intelligens ajánlás</h3>
            <p style={{ color: '#94A3B8', fontSize: '0.875rem' }}>
              AI alapú szerviz javaslatok és árbecslés
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}

function PrivateRoute({ children }) {
  return getToken() ? children : <Navigate to="/login" replace />;
}

export default function App() {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <Nav />
      <main style={{ flex: 1, paddingBottom: '20px' }}>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/register" element={<Register onAuth={(t) => setToken(t)} />} />
          <Route path="/login" element={<Login onAuth={(t) => setToken(t)} />} />
          <Route path="/for-sale" element={<ForSale />} />
          <Route path="/sales/:saleId" element={<SaleDetail />} />

          <Route path="/dashboard" element={<PrivateRoute><Dashboard /></PrivateRoute>} />
          <Route path="/cars/new" element={<PrivateRoute><NewCar /></PrivateRoute>} />
          <Route path="/cars/:carId" element={<PrivateRoute><CarDetail /></PrivateRoute>} />
          <Route path="/recommendations/:issueId" element={<PrivateRoute><Recommendation /></PrivateRoute>} />
          <Route path="/book" element={<PrivateRoute><Booking /></PrivateRoute>} />
          <Route path="/knowledge-hub" element={<PrivateRoute><KnowledgeHub /></PrivateRoute>} />
          <Route path="/profile" element={<PrivateRoute><UserProfile /></PrivateRoute>} />
          <Route path="/book-appointment" element={<PrivateRoute><BookAppointment /></PrivateRoute>} />
        </Routes>
      </main>
      <Footer />
    </div>
  );
}
