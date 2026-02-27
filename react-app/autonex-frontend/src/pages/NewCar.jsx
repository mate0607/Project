import React, { useState } from "react";
import { api } from "../api.js";
import { useNavigate } from "react-router-dom";

export default function NewCar() {
  const nav = useNavigate();
  const [makeModel, setMakeModel] = useState("");
  const [vin, setVin] = useState("");
  const [year, setYear] = useState("");
  const [err, setErr] = useState("");

  async function submit(e) {
    e.preventDefault();
    setErr("");
    try {
      await api.createCar(makeModel, vin, year ? Number(year) : null);
      nav("/dashboard");
    } catch (e) {
      setErr(e.message);
    }
  }

  return (
    <div className="form-wrapper">
      <h2>Autó hozzáadása</h2>
      {err && <div className="alert alert-error">{err}</div>}
      <form onSubmit={submit}>
        <div>
          <label>Autó típusa</label>
          <input 
            value={makeModel} 
            onChange={(e) => setMakeModel(e.target.value)} 
            required 
            placeholder="Például: BMW 320i"
          />
        </div>
        <div>
          <label>VIN (17 karakter)</label>
          <input 
            value={vin} 
            onChange={(e) => setVin(e.target.value)} 
            required 
            placeholder="WBADO6325F1234567"
            maxLength="17"
          />
        </div>
        <div>
          <label>Évjarat (opcionális)</label>
          <input 
            value={year} 
            onChange={(e) => setYear(e.target.value)} 
            type="number" 
            placeholder="2023"
            min="1900"
            max={new Date().getFullYear()}
          />
        </div>
        <button type="submit" style={{ marginTop: '10px' }}>Mentés</button>
      </form>
    </div>
  );
}