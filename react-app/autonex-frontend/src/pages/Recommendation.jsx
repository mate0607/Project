import React, { useEffect, useState } from "react";
import { api } from "../api.js";
import { useParams, Link } from "react-router-dom";

export default function Recommendation() {
  const { issueId } = useParams();
  const [data, setData] = useState(null);
  const [err, setErr] = useState("");

  useEffect(() => {
    (async () => {
      try {
        const res = await api.recommendation(issueId);
        setData(res.data);
      } catch (e) {
        setErr(e.message);
      }
    })();
  }, [issueId]);

  if (!data) return <div style={{ padding: 16, color: '#94A3B8' }}>{err || "Betöltés..."}</div>;

  return (
    <div style={{ maxWidth: 900, margin: "30px auto", padding: "0 20px" }}>
      <div className="card">
        <h2>Javasolt szerviz</h2>
        <div style={{ color: "#94A3B8", marginBottom: '20px' }}>
          Autó: <strong style={{ color: '#CBD5E1' }}>{data.make_model}</strong> | VIN: {data.vin}
        </div>
        <div style={{ marginBottom: '16px' }}>
          <p><strong style={{ color: '#818CF8' }}>Kategória:</strong> <span style={{ color: '#CBD5E1' }}>{data.category}</span></p>
          <p><strong style={{ color: '#818CF8' }}>Leírás:</strong> <span style={{ color: '#CBD5E1' }}>{data.description}</span></p>
          <p><strong style={{ color: '#818CF8' }}>Sürgősség:</strong> 
            <span className={`badge badge-${data.urgency === 'high' ? 'danger' : data.urgency === 'low' ? 'info' : 'warning'}`} 
              style={{ marginLeft: '8px', textTransform: 'capitalize' }}>
              {data.urgency}
            </span>
          </p>
        </div>
      </div>

      <div className="card">
        <h3 style={{ color: '#10B981', marginBottom: '16px' }}>Javasolt szolgáltatás</h3>
        <h4 style={{ fontSize: '1.25rem', marginBottom: '12px', color: '#F1F5F9' }}>{data.service_name}</h4>
        <p style={{ color: '#CBD5E1', marginBottom: '16px', lineHeight: '1.6' }}>{data.explanation}</p>
        {(data.estimated_price_min || data.estimated_price_max) && (
          <div style={{ 
            background: 'rgba(16, 185, 129, 0.1)',
            border: '1px solid rgba(16, 185, 129, 0.3)',
            borderRadius: '8px',
            padding: '12px',
            marginBottom: '20px',
            color: '#10B981'
          }}>
            Becsült ár: <strong>{data.estimated_price_min || "?"} – {data.estimated_price_max || "?"} Ft</strong>
          </div>
        )}
        <Link to={`/cars/${data.car_id}`}>
          <button style={{ padding: '10px 20px', fontSize: '14px' }}>Vissza az autóhoz</button>
        </Link>
      </div>
    </div>
  );
}