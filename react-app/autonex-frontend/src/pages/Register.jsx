import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { api } from "../api.js";

export default function Register({ onAuth }) {
  const nav = useNavigate();
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [err, setErr] = useState("");

  async function submit(e) {
    e.preventDefault();
    setErr("");
    try {
      const res = await api.register(name, email, password);
      onAuth(res.token);
      nav("/dashboard");
    } catch (e) {
      setErr(e.message);
    }
  }

  return (
    <div className="form-wrapper">
      <h2>Regisztráció</h2>
      {err && <div className="alert alert-error">{err}</div>}
      <form onSubmit={submit}>
        <div>
          <label>Név</label>
          <input 
            value={name} 
            onChange={(e) => setName(e.target.value)} 
            required 
            placeholder="Például: Kék Béla"
          />
        </div>
        <div>
          <label>Email</label>
          <input 
            value={email} 
            onChange={(e) => setEmail(e.target.value)} 
            required 
            type="email" 
            placeholder="ted@example.com"
          />
        </div>
        <div>
          <label>Jelszó</label>
          <input 
            value={password} 
            onChange={(e) => setPassword(e.target.value)} 
            required 
            type="password" 
            placeholder="Kellően erős jelszó"
          />
        </div>
        <button type="submit" style={{ marginTop: '10px' }}>Létrehozás</button>
      </form>
    </div>
  );
}