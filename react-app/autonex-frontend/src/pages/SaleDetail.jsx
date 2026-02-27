import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { api, getToken } from "../api.js";

export default function SaleDetail() {
  const { saleId } = useParams();
  const nav = useNavigate();
  const [sale, setSale] = useState(null);
  const [err, setErr] = useState("");
  const [loading, setLoading] = useState(true);
  const [messages, setMessages] = useState([]);
  const [messageText, setMessageText] = useState("");
  const [sendingMessage, setSendingMessage] = useState(false);

  async function load() {
    setLoading(true);
    setErr("");
    try {
      const res = await api.getAllActiveSales();
      const found = res.sales.find(s => s.id === parseInt(saleId));
      if (!found) {
        setErr("Az autó nem található");
      } else {
        setSale(found);
        
        // Load messages if user is logged in
        if (getToken()) {
          try {
            const messagesRes = await api.getMessages(saleId);
            setMessages(messagesRes.messages);
          } catch (e) {
            // Messages not critical
            console.log("Could not load messages");
          }
        }
      }
    } catch (e) {
      setErr(e.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    load();
  }, [saleId]);

  async function handleSendMessage(e) {
    e.preventDefault();
    if (!messageText.trim()) return;
    
    setSendingMessage(true);
    try {
      await api.sendMessage(parseInt(saleId), messageText);
      setMessageText("");
      // Reload messages
      const messagesRes = await api.getMessages(saleId);
      setMessages(messagesRes.messages);
    } catch (e) {
      setErr(e.message);
    } finally {
      setSendingMessage(false);
    }
  }

  if (loading) {
    return <div style={{ padding: "20px", color: "#94A3B8", textAlign: "center" }}>Betöltés...</div>;
  }

  if (!sale || err) {
    return (
      <div style={{ maxWidth: "900px", margin: "30px auto", padding: "0 20px" }}>
        <div className="card">
          <div style={{
            background: "rgba(239, 68, 68, 0.1)",
            border: "1px solid rgba(239, 68, 68, 0.3)",
            color: "#FCA5A5",
            padding: "20px",
            borderRadius: "8px",
            textAlign: "center"
          }}>
            {err || "Az autó nem található"}
          </div>
          <button
            onClick={() => nav("/for-sale")}
            style={{
              marginTop: "20px",
              padding: "10px 16px",
              background: "rgba(79, 70, 229, 0.15)",
              border: "1px solid rgba(129, 140, 248, 0.3)",
              color: "#818CF8",
              borderRadius: "6px",
              cursor: "pointer",
              fontWeight: 500
            }}
          >
            ← Vissza az eladásokra
          </button>
        </div>
      </div>
    );
  }

  return (
    <div style={{ maxWidth: "900px", margin: "30px auto", padding: "0 20px" }}>
      <button
        onClick={() => nav("/for-sale")}
        style={{
          padding: "10px 16px",
          background: "rgba(79, 70, 229, 0.15)",
          border: "1px solid rgba(129, 140, 248, 0.3)",
          color: "#818CF8",
          borderRadius: "6px",
          cursor: "pointer",
          fontWeight: 500,
          marginBottom: "20px"
        }}
      >
        ← Vissza az eladásokra
      </button>

      <div className="card">
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "start", marginBottom: "20px" }}>
          <div>
            <h1 style={{ marginBottom: "10px" }}>{sale.make_model}</h1>
            <p style={{ color: "#94A3B8", fontSize: "1rem" }}>VIN: {sale.vin}</p>
          </div>
          <div style={{
            background: "rgba(79, 70, 229, 0.1)",
            padding: "20px",
            borderRadius: "12px",
            textAlign: "right",
            border: "1px solid rgba(129, 140, 248, 0.2)"
          }}>
            <p style={{ color: "#94A3B8", marginBottom: "8px", fontSize: "0.9rem" }}>Ár</p>
            <p style={{ color: "#818CF8", fontSize: "2rem", fontWeight: "bold" }}>
              {sale.price.toLocaleString("hu-HU")} Ft
            </p>
          </div>
        </div>

        {sale.image_url && (
          <div style={{ marginBottom: "30px" }}>
            <img
              src={`http://localhost:4000${sale.image_url}`}
              alt={sale.make_model}
              style={{
                width: "100%",
                maxHeight: "400px",
                objectFit: "cover",
                borderRadius: "12px"
              }}
            />
          </div>
        )}

        <div style={{
          display: "grid",
          gridTemplateColumns: "1fr 1fr",
          gap: "20px",
          marginBottom: "30px"
        }}>
          {sale.year && (
            <div style={{
              background: "rgba(99, 102, 241, 0.08)",
              padding: "16px",
              borderRadius: "8px",
              border: "1px solid rgba(129, 140, 248, 0.15)"
            }}>
              <p style={{ color: "#94A3B8", marginBottom: "8px" }}>Gyártási év</p>
              <p style={{ color: "#F1F5F9", fontSize: "1.25rem", fontWeight: "600" }}>{sale.year}</p>
            </div>
          )}

          {sale.mileage && (
            <div style={{
              background: "rgba(99, 102, 241, 0.08)",
              padding: "16px",
              borderRadius: "8px",
              border: "1px solid rgba(129, 140, 248, 0.15)"
            }}>
              <p style={{ color: "#94A3B8", marginBottom: "8px" }}>Futásteljesítmény</p>
              <p style={{ color: "#F1F5F9", fontSize: "1.25rem", fontWeight: "600" }}>
                {sale.mileage.toLocaleString("hu-HU")} km
              </p>
            </div>
          )}

          {sale.car_condition && (
            <div style={{
              background: "rgba(99, 102, 241, 0.08)",
              padding: "16px",
              borderRadius: "8px",
              border: "1px solid rgba(129, 140, 248, 0.15)"
            }}>
              <p style={{ color: "#94A3B8", marginBottom: "8px" }}>Állapot</p>
              <p style={{ color: "#F1F5F9", fontSize: "1.25rem", fontWeight: "600" }}>
                {sale.car_condition}
              </p>
            </div>
          )}
        </div>

        {sale.description && (
          <div style={{
            background: "rgba(99, 102, 241, 0.08)",
            padding: "20px",
            borderRadius: "8px",
            border: "1px solid rgba(129, 140, 248, 0.15)",
            marginBottom: "30px"
          }}>
            <p style={{ color: "#94A3B8", marginBottom: "12px", fontWeight: "600" }}>Leírás</p>
            <p style={{ color: "#CBD5E1", lineHeight: "1.6", whiteSpace: "pre-wrap" }}>
              {sale.description}
            </p>
          </div>
        )}

        <div style={{
          background: "rgba(99, 102, 241, 0.05)",
          padding: "16px",
          borderRadius: "8px",
          border: "1px solid rgba(129, 140, 248, 0.15)",
          marginBottom: "20px"
        }}>
          <p style={{ color: "#94A3B8", marginBottom: "8px" }}>Eladó</p>
          <p style={{ color: "#F1F5F9", fontSize: "1.1rem", fontWeight: "600", marginBottom: "8px" }}>
            {sale.seller_name}
          </p>
          <p style={{ color: "#94A3B8", fontSize: "0.9rem" }}>
            Felsorolt: {new Date(sale.created_at).toLocaleDateString("hu-HU")}
          </p>
        </div>

        <div style={{
          background: "rgba(34, 197, 94, 0.1)",
          border: "1px solid rgba(34, 197, 94, 0.3)",
          borderRadius: "8px",
          padding: "20px",
          textAlign: "center"
        }}>
          {getToken() ? (
            <div>
              <h3 style={{ color: "#22C55E", marginBottom: "16px" }}>Írj üzenetet az eladónak</h3>
              
              {/* Messages list */}
              {messages.length > 0 && (
                <div style={{
                  background: "rgba(0, 0, 0, 0.2)",
                  borderRadius: "8px",
                  padding: "16px",
                  marginBottom: "20px",
                  maxHeight: "300px",
                  overflowY: "auto"
                }}>
                  {messages.map(msg => (
                    <div key={msg.id} style={{
                      marginBottom: "12px",
                      padding: "10px",
                      background: "rgba(99, 102, 241, 0.1)",
                      borderRadius: "6px",
                      borderLeft: "3px solid rgba(129, 140, 248, 0.5)"
                    }}>
                      <p style={{ color: "#818CF8", fontWeight: "600", marginBottom: "4px" }}>
                        {msg.sender_name}
                      </p>
                      <p style={{ color: "#CBD5E1", marginBottom: "4px" }}>{msg.message}</p>
                      <p style={{ color: "#94A3B8", fontSize: "0.8rem" }}>
                        {new Date(msg.created_at).toLocaleDateString("hu-HU")} {new Date(msg.created_at).toLocaleTimeString("hu-HU")}
                      </p>
                    </div>
                  ))}
                </div>
              )}

              {/* Message form */}
              <form onSubmit={handleSendMessage}>
                <textarea
                  value={messageText}
                  onChange={(e) => setMessageText(e.target.value)}
                  placeholder="Írj egy üzenetet..."
                  rows={4}
                  style={{
                    width: "100%",
                    padding: "12px",
                    background: "rgba(0, 0, 0, 0.3)",
                    border: "1px solid rgba(129, 140, 248, 0.3)",
                    borderRadius: "6px",
                    color: "#F1F5F9",
                    marginBottom: "12px",
                    fontFamily: "inherit",
                    resize: "vertical"
                  }}
                  required
                />
                <button
                  type="submit"
                  disabled={sendingMessage || !messageText.trim()}
                  style={{
                    padding: "10px 20px",
                    background: "linear-gradient(135deg, #818CF8 0%, #6366F1 100%)",
                    border: "none",
                    color: "#fff",
                    borderRadius: "6px",
                    cursor: sendingMessage ? "not-allowed" : "pointer",
                    fontWeight: 600,
                    fontSize: "1rem",
                    transition: "all 0.2s",
                    opacity: sendingMessage || !messageText.trim() ? 0.6 : 1
                  }}
                  onMouseEnter={(e) => {
                    if (!sendingMessage && messageText.trim()) {
                      e.target.style.transform = "translateY(-2px)";
                      e.target.style.boxShadow = "0 6px 20px rgba(129, 140, 248, 0.4)";
                    }
                  }}
                  onMouseLeave={(e) => {
                    e.target.style.transform = "translateY(0)";
                    e.target.style.boxShadow = "none";
                  }}
                >
                  {sendingMessage ? "Küldés..." : "Üzenet küldése"}
                </button>
              </form>
            </div>
          ) : (
            <div>
              <p style={{ color: "#94A3B8", marginBottom: "12px" }}>
                Az eladóval való kapcsolatfelvételhez kérjük, lépj be az alkalmazásba
              </p>
              <button
                onClick={() => nav("/login")}
                style={{
                  padding: "10px 20px",
                  background: "linear-gradient(135deg, #818CF8 0%, #6366F1 100%)",
                  border: "none",
                  color: "#fff",
                  borderRadius: "6px",
                  cursor: "pointer",
                  fontWeight: 600,
                  fontSize: "1rem",
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
                Belépés
              </button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
