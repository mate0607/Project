import React, { useState, useEffect } from "react";
import { api } from "../api.js";

export default function UserProfile() {
  const [user, setUser] = useState({ name: "", email: "", phone: "" });
  const [editMode, setEditMode] = useState(false);
  const [loading, setLoading] = useState(true);
  const [msg, setMsg] = useState("");
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    api.getProfile()
      .then(res => {
        setUser(res);
        setLoading(false);
      })
      .catch(err => {
        console.error(err);
        setMsg("Hiba a profil betöltésekor");
        setLoading(false);
      });
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setUser(prev => ({ ...prev, [name]: value }));
  };

  const handleSave = async (e) => {
    e.preventDefault();
    setSaving(true);
    setMsg("");

    try {
      await api.updateProfile(user.name, user.email, user.phone);
      setMsg("Profil sikeresen frissítve!");
      setEditMode(false);
    } catch (err) {
      setMsg("Hiba a profil frissítésekor: " + err.message);
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return (
      <div style={{ maxWidth: '800px', margin: '30px auto', padding: '0 20px' }}>
        <h2>Profil</h2>
        <div style={{ textAlign: 'center', padding: '60px 40px' }}>
          <div className="spinner"></div>
          <p style={{ color: '#94A3B8', marginTop: '20px' }}>Profil betöltése...</p>
        </div>
      </div>
    );
  }

  return (
    <div style={{ maxWidth: '800px', margin: '30px auto', padding: '0 20px' }}>
      <h2 style={{ marginBottom: '20px' }}>Profil</h2>

      {msg && (
        <div className={msg.includes("Hiba") ? "alert alert-error" : "alert alert-success"}>
          {msg}
        </div>
      )}

      <div className="card">
        {!editMode ? (
          <div>
            <div style={{ marginBottom: '24px' }}>
              <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: '0 0 4px 0', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                Név
              </p>
              <p style={{ color: '#F1F5F9', margin: 0, fontSize: '1.125rem', fontWeight: '600' }}>
                {user.name}
              </p>
            </div>

            <div style={{ marginBottom: '24px' }}>
              <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: '0 0 4px 0', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                Email
              </p>
              <p style={{ color: '#F1F5F9', margin: 0, fontSize: '1.125rem', fontWeight: '600' }}>
                {user.email}
              </p>
            </div>

            <div style={{ marginBottom: '24px' }}>
              <p style={{ color: '#94A3B8', fontSize: '0.75rem', margin: '0 0 4px 0', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                Telefon
              </p>
              <p style={{ color: '#F1F5F9', margin: 0, fontSize: '1.125rem', fontWeight: '600' }}>
                {user.phone || "Nincs megadva"}
              </p>
            </div>

            <button 
              onClick={() => setEditMode(true)}
              style={{ marginTop: '20px', padding: '10px 20px', fontSize: '14px' }}
            >
              Szerkesztés
            </button>
          </div>
        ) : (
          <form onSubmit={handleSave}>
            <div style={{ marginBottom: '16px' }}>
              <label>Név</label>
              <input 
                type="text"
                name="name"
                value={user.name}
                onChange={handleChange}
                required
              />
            </div>

            <div style={{ marginBottom: '16px' }}>
              <label>Email</label>
              <input 
                type="email"
                name="email"
                value={user.email}
                onChange={handleChange}
                required
              />
            </div>

            <div style={{ marginBottom: '20px' }}>
              <label>Telefon</label>
              <input 
                type="tel"
                name="phone"
                value={user.phone || ""}
                onChange={handleChange}
                placeholder="+36 1 2345 6789"
              />
            </div>

            <div style={{ display: 'flex', gap: '12px' }}>
              <button 
                type="submit"
                disabled={saving}
                style={{ padding: '10px 20px', fontSize: '14px' }}
              >
                {saving ? "Mentés..." : "Mentés"}
              </button>
              <button 
                type="button"
                onClick={() => setEditMode(false)}
                className="secondary"
                style={{ padding: '10px 20px', fontSize: '14px' }}
              >
                Mégse
              </button>
            </div>
          </form>
        )}
      </div>
    </div>
  );
}
