import React, { useEffect, useState } from "react";
import { api } from "../api.js";
import { useNavigate } from "react-router-dom";

export default function ForSale() {
  const [sales, setSales] = useState([]);
  const [loading, setLoading] = useState(true);
  const [err, setErr] = useState("");
  const nav = useNavigate();

  async function load() {
    setLoading(true);
    setErr("");
    try {
      const res = await api.getAllActiveSales();
      setSales(res.sales);
    } catch (e) {
      setErr(e.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    load();
  }, []);

  if (loading) {
    return <div style={{ padding: "20px", color: "#94A3B8", textAlign: "center" }}>Betöltés...</div>;
  }

  return (
    <div style={{ maxWidth: "1200px", margin: "30px auto", padding: "0 20px" }}>
      <div className="card">
        <h1 style={{ marginBottom: "10px" }}>Eladásra Bocsájtott Autók</h1>
        <p style={{ color: "#94A3B8", marginBottom: "30px" }}>
          Böngészd az eladásra felsorolt autókat
        </p>

        {err && (
          <div style={{
            background: "rgba(239, 68, 68, 0.1)",
            border: "1px solid rgba(239, 68, 68, 0.3)",
            color: "#FCA5A5",
            padding: "12px 16px",
            borderRadius: "8px",
            marginBottom: "20px"
          }}>
            {err}
          </div>
        )}

        {sales.length === 0 ? (
          <div style={{ textAlign: "center", padding: "60px 20px" }}>
            <p style={{ color: "#94A3B8", fontSize: "1.1rem", marginBottom: "20px" }}>
              Jelenleg nincs eladásra felsorolt autó
            </p>
          </div>
        ) : (
          <div style={{
            display: "grid",
            gridTemplateColumns: "repeat(auto-fill, minmax(300px, 1fr))",
            gap: "20px"
          }}>
            {sales.map(sale => (
              <div key={sale.id} className="card" style={{ cursor: "pointer", transition: "all 0.3s" }}>
                {sale.image_url && (
                  <img
                    src={`http://localhost:4000${sale.image_url}`}
                    alt={sale.make_model}
                    style={{
                      width: "100%",
                      height: "250px",
                      objectFit: "cover",
                      borderRadius: "8px",
                      marginBottom: "16px"
                    }}
                  />
                )}

                <h3 style={{ color: "#F1F5F9", marginBottom: "8px" }}>
                  {sale.make_model}
                </h3>

                <div style={{ marginBottom: "12px" }}>
                  <p style={{ color: "#94A3B8", fontSize: "0.9rem", marginBottom: "4px" }}>
                    VIN: {sale.vin}
                  </p>
                  {sale.year && (
                    <p style={{ color: "#94A3B8", fontSize: "0.9rem", marginBottom: "4px" }}>
                      Év: {sale.year}
                    </p>
                  )}
                </div>

                <div style={{
                  background: "rgba(79, 70, 229, 0.1)",
                  padding: "12px",
                  borderRadius: "8px",
                  marginBottom: "12px"
                }}>
                  <p style={{ color: "#818CF8", fontSize: "1.5rem", fontWeight: "bold" }}>
                    {sale.price.toLocaleString("hu-HU")} Ft
                  </p>
                </div>

                {sale.car_condition && (
                  <p style={{ color: "#CBD5E1", marginBottom: "8px" }}>
                    <strong>Állapot:</strong> {sale.car_condition}
                  </p>
                )}

                {sale.mileage && (
                  <p style={{ color: "#CBD5E1", marginBottom: "8px" }}>
                    <strong>Futásteljesítmény:</strong> {sale.mileage.toLocaleString("hu-HU")} km
                  </p>
                )}

                {sale.description && (
                  <p style={{ color: "#CBD5E1", marginBottom: "12px", fontSize: "0.9rem" }}>
                    <strong>Leírás:</strong><br />
                    {sale.description}
                  </p>
                )}

                <div style={{
                  borderTop: "1px solid rgba(148, 163, 184, 0.2)",
                  paddingTop: "12px",
                  marginTop: "12px",
                  display: "flex",
                  justifyContent: "space-between",
                  alignItems: "center"
                }}>
                  <div>
                    <p style={{ color: "#94A3B8", fontSize: "0.85rem", marginBottom: "4px" }}>
                      Eladó: {sale.seller_name}
                    </p>
                    <p style={{ color: "#94A3B8", fontSize: "0.85rem" }}>
                      {new Date(sale.created_at).toLocaleDateString("hu-HU")}
                    </p>
                  </div>
                  <button
                    onClick={() => nav(`/sales/${sale.id}`)}
                    style={{
                      padding: "8px 14px",
                      background: "linear-gradient(135deg, #818CF8 0%, #6366F1 100%)",
                      border: "none",
                      color: "#fff",
                      borderRadius: "6px",
                      cursor: "pointer",
                      fontWeight: 500,
                      fontSize: "0.9rem",
                      transition: "all 0.2s"
                    }}
                    onMouseEnter={(e) => {
                      e.target.style.transform = "translateY(-2px)";
                      e.target.style.boxShadow = "0 6px 20px rgba(129, 140, 248, 0.4)";
                    }}
                    onMouseLeave={(e) => {
                      e.target.style.transform = "translateY(0)";
                      e.target.style.boxShadow = "none";
                    }}
                  >
                    Részletek
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
