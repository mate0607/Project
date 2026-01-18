/**
 * Car Image Upload Component
 * Allows users to upload and manage car photos
 */

import { useState } from 'react';
import { api } from '../api';

export default function CarImageUpload({ carId, currentImageUrl, onImageUploaded, onImageDeleted }) {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [preview, setPreview] = useState(null);
  const [selectedFile, setSelectedFile] = useState(null);

  const handleFileSelect = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;

    // Validate file type
    if (!file.type.startsWith('image/')) {
      setError('Csak képfájlok engedélyezettek');
      return;
    }

    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
      setError('A fájl túl nagy (max 5MB)');
      return;
    }

    setSelectedFile(file);
    setError('');

    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => setPreview(e.target?.result);
    reader.readAsDataURL(file);
  };

  const handleUpload = async () => {
    if (!selectedFile) {
      setError('Válassz egy képet');
      return;
    }

    try {
      setLoading(true);
      setError('');

      const formData = new FormData();
      formData.append('carId', carId);
      formData.append('image', selectedFile);

      const response = await fetch('http://localhost:4000/api/cars/upload-image', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: formData
      });

      if (!response.ok) {
        const data = await response.json();
        throw new Error(data.error || 'Feltöltés sikertelen');
      }

      const data = await response.json();
      setPreview(null);
      setSelectedFile(null);
      
      if (onImageUploaded) {
        onImageUploaded(data.imageUrl);
      }

      // Success notification
      alert('Kép sikeresen feltöltve!');
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleDeleteImage = async () => {
    if (!window.confirm('Biztosan törölni szeretnéd a képet?')) return;

    try {
      setLoading(true);
      setError('');

      const response = await fetch(`http://localhost:4000/api/cars/${carId}/image`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      });

      if (!response.ok) {
        const data = await response.json();
        throw new Error(data.error || 'Törlés sikertelen');
      }

      if (onImageDeleted) {
        onImageDeleted();
      }

      alert('Kép sikeresen törölve!');
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{
      background: 'linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(79, 70, 229, 0.12) 50%, rgba(15, 23, 42, 0.95) 100%)',
      padding: '24px',
      borderRadius: '12px',
      border: '1.5px solid rgba(129, 140, 248, 0.3)',
      marginBottom: '20px'
    }}>
      <h3 style={{ marginBottom: '16px', color: '#F1F5F9' }}>
        Autó fotó feltöltés
      </h3>

      {error && (
        <div style={{
          background: 'rgba(239, 68, 68, 0.15)',
          color: '#FCA5A5',
          padding: '12px 16px',
          borderRadius: '8px',
          marginBottom: '16px',
          fontSize: '14px',
          border: '1px solid rgba(239, 68, 68, 0.3)'
        }}>
          {error}
        </div>
      )}

      {/* Preview */}
      {preview && (
        <div style={{ marginBottom: '16px' }}>
          <img 
            src={preview} 
            alt="Preview" 
            style={{
              maxWidth: '100%',
              maxHeight: '300px',
              borderRadius: '8px',
              border: '1px solid rgba(129, 140, 248, 0.3)'
            }}
          />
        </div>
      )}

      {/* File Input */}
      <div style={{
        display: 'flex',
        gap: '12px',
        marginBottom: '16px'
      }}>
        <label style={{
          flex: 1,
          padding: '12px 16px',
          borderRadius: '8px',
          border: '2px dashed rgba(129, 140, 248, 0.5)',
          cursor: 'pointer',
          textAlign: 'center',
          transition: 'all 0.3s ease',
          background: 'rgba(129, 140, 248, 0.05)'
        }}
        onMouseEnter={(e) => {
          e.currentTarget.style.borderColor = 'rgba(129, 140, 248, 0.8)';
          e.currentTarget.style.background = 'rgba(129, 140, 248, 0.1)';
        }}
        onMouseLeave={(e) => {
          e.currentTarget.style.borderColor = 'rgba(129, 140, 248, 0.5)';
          e.currentTarget.style.background = 'rgba(129, 140, 248, 0.05)';
        }}
        >
          <input
            type="file"
            accept="image/*"
            onChange={handleFileSelect}
            style={{ display: 'none' }}
            disabled={loading}
          />
          <span style={{ color: '#94A3B8', fontSize: '14px' }}>
            {selectedFile ? selectedFile.name : 'Kattints a képhez vagy drag & drop'}
          </span>
        </label>
      </div>

      {/* Upload Button */}
      <button
        onClick={handleUpload}
        disabled={!selectedFile || loading}
        style={{
          width: '100%',
          padding: '12px 16px',
          opacity: !selectedFile || loading ? 0.6 : 1
        }}
      >
        {loading ? 'Feltöltés...' : 'Feltöltés'}
      </button>

      {/* Delete Button - Show only if image exists */}
      {currentImageUrl && (
        <button
          onClick={handleDeleteImage}
          disabled={loading}
          style={{
            width: '100%',
            padding: '12px 16px',
            marginTop: '12px',
            background: 'rgba(239, 68, 68, 0.2)',
            border: '1px solid rgba(239, 68, 68, 0.3)',
            color: '#EF4444',
            opacity: loading ? 0.6 : 1
          }}
        >
          {loading ? 'Törlés...' : 'Kép törlése'}
        </button>
      )}

      {/* Info */}
      <p style={{
        fontSize: '12px',
        color: '#64748B',
        marginTop: '12px',
        margin: '12px 0 0 0'
      }}>
        Támogatott: JPG, PNG, WebP | Max: 5MB
      </p>
    </div>
  );
}
