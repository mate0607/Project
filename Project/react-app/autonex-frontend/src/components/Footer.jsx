import { Link } from 'react-router-dom';

export default function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer style={{
      background: 'linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(45, 27, 78, 0.5) 100%)',
      backdropFilter: 'blur(10px)',
      borderTop: '1px solid rgba(129, 140, 248, 0.3)',
      marginTop: '40px',
      padding: '20px',
      color: '#CBD5E1',
      fontSize: '13px'
    }}>
      <div style={{ maxWidth: '1200px', margin: '0 auto' }}>
        {/* Main Footer Content */}
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))',
          gap: '20px',
          marginBottom: '16px'
        }}>
          {/* Brand */}
          <div>
            <h4 style={{
              fontSize: '14px',
              fontWeight: 700,
              marginBottom: '8px',
              color: '#818CF8'
            }}>
              AutoNex
            </h4>
            <p style={{ color: '#94A3B8', fontSize: '12px', margin: '0 0 8px 0' }}>
              Okos autókezelés
            </p>
            <div style={{ display: 'flex', gap: '8px' }}>
              <a href="#" style={{
                width: '28px',
                height: '28px',
                borderRadius: '50%',
                background: 'rgba(129, 140, 248, 0.15)',
                border: '1px solid rgba(129, 140, 248, 0.3)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                color: '#818CF8',
                textDecoration: 'none',
                transition: 'all 0.2s ease',
                fontSize: '12px',
                fontWeight: 700
              }}
              onMouseEnter={(e) => e.target.style.background = 'rgba(129, 140, 248, 0.25)'}
              onMouseLeave={(e) => e.target.style.background = 'rgba(129, 140, 248, 0.15)'}
              >
                f
              </a>
              <a href="#" style={{
                width: '28px',
                height: '28px',
                borderRadius: '50%',
                background: 'rgba(129, 140, 248, 0.15)',
                border: '1px solid rgba(129, 140, 248, 0.3)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                color: '#818CF8',
                textDecoration: 'none',
                transition: 'all 0.2s ease',
                fontSize: '11px'
              }}
              onMouseEnter={(e) => e.target.style.background = 'rgba(129, 140, 248, 0.25)'}
              onMouseLeave={(e) => e.target.style.background = 'rgba(129, 140, 248, 0.15)'}
              >
                𝕏
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 style={{ fontSize: '13px', fontWeight: 700, marginBottom: '8px', color: '#F1F5F9', margin: '0 0 8px 0' }}>
              Linkek
            </h4>
            <ul style={{ listStyle: 'none', padding: 0, margin: 0 }}>
              <li style={{ marginBottom: '6px' }}>
                <Link to="/dashboard" style={{
                  color: '#CBD5E1',
                  textDecoration: 'none',
                  fontSize: '12px',
                  transition: 'all 0.2s ease'
                }}
                onMouseEnter={(e) => e.target.style.color = '#818CF8'}
                onMouseLeave={(e) => e.target.style.color = '#CBD5E1'}
                >
                  Dashboard
                </Link>
              </li>
              <li style={{ marginBottom: '6px' }}>
                <Link to="/book" style={{
                  color: '#CBD5E1',
                  textDecoration: 'none',
                  fontSize: '12px',
                  transition: 'all 0.2s ease'
                }}
                onMouseEnter={(e) => e.target.style.color = '#818CF8'}
                onMouseLeave={(e) => e.target.style.color = '#CBD5E1'}
                >
                  Foglalás
                </Link>
              </li>
              <li>
                <Link to="/profile" style={{
                  color: '#CBD5E1',
                  textDecoration: 'none',
                  fontSize: '12px',
                  transition: 'all 0.2s ease'
                }}
                onMouseEnter={(e) => e.target.style.color = '#818CF8'}
                onMouseLeave={(e) => e.target.style.color = '#CBD5E1'}
                >
                  Profil
                </Link>
              </li>
            </ul>
          </div>

          {/* Support */}
          <div>
            <h4 style={{ fontSize: '13px', fontWeight: 700, marginBottom: '8px', color: '#F1F5F9', margin: '0 0 8px 0' }}>
              Támogatás
            </h4>
            <ul style={{ listStyle: 'none', padding: 0, margin: 0 }}>
              <li style={{ marginBottom: '6px' }}>
                <a href="#" style={{
                  color: '#CBD5E1',
                  textDecoration: 'none',
                  fontSize: '12px',
                  transition: 'all 0.2s ease'
                }}
                onMouseEnter={(e) => e.target.style.color = '#818CF8'}
                onMouseLeave={(e) => e.target.style.color = '#CBD5E1'}
                >
                  Súgó
                </a>
              </li>
              <li style={{ marginBottom: '6px' }}>
                <a href="#" style={{
                  color: '#CBD5E1',
                  textDecoration: 'none',
                  fontSize: '12px',
                  transition: 'all 0.2s ease'
                }}
                onMouseEnter={(e) => e.target.style.color = '#818CF8'}
                onMouseLeave={(e) => e.target.style.color = '#CBD5E1'}
                >
                  Jogi
                </a>
              </li>
              <li>
                <a href="#" style={{
                  color: '#CBD5E1',
                  textDecoration: 'none',
                  fontSize: '12px',
                  transition: 'all 0.2s ease'
                }}
                onMouseEnter={(e) => e.target.style.color = '#818CF8'}
                onMouseLeave={(e) => e.target.style.color = '#CBD5E1'}
                >
                  Adatvédelem
                </a>
              </li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 style={{ fontSize: '13px', fontWeight: 700, marginBottom: '8px', color: '#F1F5F9', margin: '0 0 8px 0' }}>
              Kapcsolat
            </h4>
            <p style={{ fontSize: '12px', color: '#94A3B8', margin: '0 0 4px 0' }}>
              📧 support@autonex.hu
            </p>
            <p style={{ fontSize: '12px', color: '#94A3B8', margin: '0 0 4px 0' }}>
              📞 +36 1 234 5678
            </p>
            <p style={{ fontSize: '12px', color: '#94A3B8', margin: 0 }}>
              ⏰ H-P 8:00-18:00
            </p>
          </div>
        </div>

        {/* Bottom */}
        <div style={{
          textAlign: 'center',
          paddingTop: '12px',
          borderTop: '1px solid rgba(129, 140, 248, 0.15)'
        }}>
          <p style={{ fontSize: '11px', color: '#64748B', margin: 0 }}>
            © {currentYear} AutoNex. Minden jog fenntartva.
          </p>
        </div>
      </div>
    </footer>
  );
}
