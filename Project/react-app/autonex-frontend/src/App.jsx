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
import Footer from "./components/Footer.jsx";
import "./App.css";


function Nav() {
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
        <Link to="/" style={{ 
          color: '#fff',
          textDecoration: 'none',
          fontWeight: 700,
          fontSize: '1.5rem',
          background: 'linear-gradient(135deg, #818CF8 0%, #4F46E5 100%)',
          WebkitBackgroundClip: 'text',
          WebkitTextFillColor: 'transparent',
          backgroundClip: 'text'
        }}>
          AutoNex
        </Link>
        <div style={{ display: 'flex', gap: 16, alignItems: 'center' }}>
          {token ? (
            <>
              <Link to="/dashboard" style={{ 
                color: '#CBD5E1',
                textDecoration: 'none',
                fontWeight: 500,
                padding: '8px 14px',
                borderRadius: '6px',
                transition: 'all 0.2s'
              }}>
                Dashboard
              </Link>
              <Link to="/book" style={{ 
                color: '#CBD5E1',
                textDecoration: 'none',
                fontWeight: 500,
                padding: '8px 14px',
                borderRadius: '6px',
                transition: 'all 0.2s'
              }}>
                Időpont foglalás
              </Link>
              <Link to="/knowledge-hub" style={{ 
                color: '#CBD5E1',
                textDecoration: 'none',
                fontWeight: 500,
                padding: '8px 14px',
                borderRadius: '6px',
                transition: 'all 0.2s'
              }}>
                Tudásbázis
              </Link>
              <Link to="/profile" style={{ 
                color: '#CBD5E1',
                textDecoration: 'none',
                fontWeight: 500,
                padding: '8px 14px',
                borderRadius: '6px',
                transition: 'all 0.2s'
              }}>
                Profil
              </Link>
              <a href="/locations" style={{ 
                color: '#F59E0B',
                textDecoration: 'none',
                fontWeight: 500,
                padding: '8px 14px',
                borderRadius: '6px',
                transition: 'all 0.2s'
              }}>
                Szervizpontok
              </a>
              <button
                onClick={() => { clearToken(); nav("/login"); }}
                style={{ 
                  padding: '8px 16px',
                  fontSize: '14px',
                  background: 'rgba(239, 68, 68, 0.2)',
                  border: '1px solid rgba(239, 68, 68, 0.3)',
                  color: '#EF4444',
                  boxShadow: 'none'
                }}
              >
                Kilépés
              </button>
            </>
          ) : (
            <>
              <Link to="/login" style={{ 
                color: '#CBD5E1',
                fontWeight: 500,
                padding: '8px 14px'
              }}>
                Belépés
              </Link>
              <Link to="/register">
                <button style={{ padding: '8px 16px', fontSize: '14px' }}>
                  Regisztráció
                </button>
              </Link>
            </>
          )}
        </div>
      </div>
    </nav>
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
