const API = "http://localhost:4000/api";

export function getToken() {
  return localStorage.getItem("token");
}

export function setToken(token) {
  localStorage.setItem("token", token);
}

export function clearToken() {
  localStorage.removeItem("token");
}

async function request(path, { method = "GET", body } = {}) {
  const headers = { "Content-Type": "application/json" };
  const token = getToken();
  if (token) headers.Authorization = `Bearer ${token}`;

  const res = await fetch(`${API}${path}`, {
    method,
    headers,
    body: body ? JSON.stringify(body) : undefined,
  });

  const data = await res.json().catch(() => ({}));
  if (!res.ok) throw new Error(data.error || "Hiba történt");
  return data;
}

export const api = {
  health: () => request("/health"),
  register: (name, email, password) => request("/auth/register", { method: "POST", body: { name, email, password } }),
  login: (email, password) => request("/auth/login", { method: "POST", body: { email, password } }),
  cars: () => request("/cars"),
  createCar: (make_model, vin, year) => request("/cars", { method: "POST", body: { make_model, vin, year } }),
  carDetail: (carId) => request(`/cars/${carId}`),
  createIssue: (carId, category, description, urgency) =>
    request(`/cars/${carId}/issues`, { method: "POST", body: { category, description, urgency } }),
  recommendation: (issueId) => request(`/recommendations/${issueId}`),
  createAppointment: (car_id, date, time, service) =>
    request("/appointments", { method: "POST", body: { car_id, date, time, service } }),
  getMyAppointments: () => request("/appointments/my"),
  getAllAppointments: () => request("/appointments/admin"),
  deleteAppointment: (id) => request(`/appointments/${id}`, { method: "DELETE" }),
  updateAppointment: (id, date, time, service) =>
    request(`/appointments/${id}`, { method: "PUT", body: { date, time, service } }),
  updateAppointmentStatus: (id, status) =>
    request(`/appointments/${id}/status`, { method: "PUT", body: { status } }),
  getProfile: () => request("/auth/profile"),
  updateProfile: (name, email, phone) => request("/auth/profile", { method: "PUT", body: { name, email, phone } }),
  deleteCar: (carId) => request(`/cars/${carId}`, { method: "DELETE" }),
  updateCar: (carId, make_model, vin, year) =>
    request(`/cars/${carId}`, { method: "PUT", body: { make_model, vin, year } }),
};
