import React, { useEffect, useMemo, useState } from "react";
import { api } from "../../api";
import "../../App.css";

const statusLabels = {
  pending: "Függőben",
  confirmed: "Visszaigazolt",
  in_progress: "Folyamatban",
  completed: "Kész",
  cancelled: "Törölve",
};

const statusColors = {
  pending: "#FBBF24",
  confirmed: "#38BDF8",
  in_progress: "#818CF8",
  completed: "#10B981",
  cancelled: "#EF4444",
};

export default function ServiceDashboard() {
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [filter, setFilter] = useState("all");
  const [updatingId, setUpdatingId] = useState(null);
  const [statusDrafts, setStatusDrafts] = useState({});

  useEffect(() => {
    loadAppointments();
  }, []);

  async function loadAppointments() {
    setError("");
    setLoading(true);
    try {
      const data = await api.getAllAppointments();
      setAppointments(data);
    } catch (err) {
      setError(err.message || "Nem sikerült betölteni az időpontokat.");
    } finally {
      setLoading(false);
    }
  }

  const filtered = useMemo(() => {
    if (filter === "all") return appointments;
    return appointments.filter((a) => a.status === filter);
  }, [appointments, filter]);

  function formatDate(date, time) {
    if (!date) return "";
    try {
      const composed = time ? `${date}T${time}` : date;
      const d = new Date(composed);
      return `${d.toLocaleDateString()} ${time || d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}`;
    } catch (err) {
      return `${date}${time ? ` ${time}` : ""}`;
    }
  }

  async function updateStatus(id, status) {
    setError("");
    setUpdatingId(id);
    try {
      await api.updateAppointmentStatus(id, status);
      setAppointments((prev) => prev.map((a) => (a.id === id ? { ...a, status } : a)));
    } catch (err) {
      setError(err.message || "Nem sikerült frissíteni a státuszt.");
    } finally {
      setUpdatingId(null);
    }
  }

  return (
    <div className="page">
      <div className="card" style={{ marginBottom: 24 }}>
        <div className="card-header">
          <div>
            <h1 className="card-title">Szerviz foglalások</h1>
            <p style={{ color: "#94A3B8", marginTop: 6 }}>Teljes lista minden ügyféltől, állapot kezeléssel.</p>
          </div>
          <div style={{ display: "flex", gap: 12, alignItems: "center" }}>
            <label style={{ color: "#CBD5E1", fontWeight: 600 }}>Szűrő:</label>
            <select
              value={filter}
              onChange={(e) => setFilter(e.target.value)}
              style={{ minWidth: 160 }}
            >
              <option value="all">Összes</option>
              <option value="pending">Függőben</option>
              <option value="confirmed">Visszaigazolt</option>
              <option value="in_progress">Folyamatban</option>
              <option value="completed">Kész</option>
              <option value="cancelled">Törölve</option>
            </select>
          </div>
        </div>
        {error && (
          <div style={{ color: "#F87171", marginBottom: 12 }}>{error}</div>
        )}
        {loading ? (
          <p style={{ color: "#CBD5E1" }}>Betöltés...</p>
        ) : filtered.length === 0 ? (
          <p style={{ color: "#CBD5E1" }}>Nincs megjeleníthető foglalás.</p>
        ) : (
          <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fill, minmax(320px, 1fr))", gap: 16 }}>
            {filtered.map((a) => (
              <div key={a.id} className="card" style={{ marginBottom: 0 }}>
                <div className="card-header" style={{ marginBottom: 8 }}>
                  <div>
                    <div style={{ fontWeight: 700, color: "#E2E8F0" }}>{a.user_name || "Ismeretlen ügyfél"}</div>
                    <div style={{ color: "#94A3B8", fontSize: 14 }}>{a.user_email || "-"}</div>
                  </div>
                  <span
                    className="badge"
                    style={{
                      background: `linear-gradient(135deg, ${statusColors[a.status] || "#64748B"} 0%, #0F172A 100%)`,
                      color: "#0B1224",
                      fontWeight: 700,
                    }}
                  >
                    {statusLabels[a.status] || "Ismeretlen"}
                  </span>
                </div>

                <div style={{ display: "flex", flexDirection: "column", gap: 10, marginBottom: 12 }}>
                  <div style={{ color: "#CBD5E1" }}><strong>Időpont:</strong> {formatDate(a.date, a.time)}</div>
                  <div style={{ color: "#CBD5E1" }}><strong>Szolgáltatás:</strong> {a.service || "-"}</div>
                  <div style={{ color: "#CBD5E1" }}><strong>Autó:</strong> {a.car_name || "-"} ({a.car_vin || ""})</div>
                  {a.user_phone && (
                    <div style={{ color: "#CBD5E1" }}><strong>Telefon:</strong> {a.user_phone}</div>
                  )}
                </div>

                <div style={{ display: "flex", gap: 10, alignItems: "center" }}>
                  <select
                    value={statusDrafts[a.id] ?? a.status ?? "pending"}
                    onChange={(e) => setStatusDrafts((prev) => ({ ...prev, [a.id]: e.target.value }))}
                    style={{ flex: 1 }}
                    disabled={updatingId === a.id}
                  >
                    {Object.keys(statusLabels).map((key) => (
                      <option key={key} value={key}>{statusLabels[key]}</option>
                    ))}
                  </select>
                  <button
                    onClick={() => updateStatus(a.id, statusDrafts[a.id] ?? a.status ?? "pending")}
                    disabled={updatingId === a.id}
                    className="secondary"
                  >
                    {updatingId === a.id ? "Mentés..." : "Státusz mentése"}
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
