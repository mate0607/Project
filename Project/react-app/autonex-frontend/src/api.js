import mockApi from "./mockApi";

const API = "http://localhost:4000/api";
const USE_MOCK = true; // Módosítsd false-ra ha van MySQL backend

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

// Ha USE_MOCK true, então mock api-t használ, egyébként valódi backend
const apiSource = USE_MOCK ? mockApi : { request };

export const api = {
  health: () => USE_MOCK ? mockApi.health() : request("/health"),
  register: (name, email, password) => USE_MOCK ? mockApi.register(name, email, password) : request("/auth/register", { method: "POST", body: { name, email, password } }),
  login: (email, password) => USE_MOCK ? mockApi.login(email, password) : request("/auth/login", { method: "POST", body: { email, password } }),
  cars: () => USE_MOCK ? mockApi.cars() : request("/cars"),
  createCar: (make_model, vin, year) => USE_MOCK ? mockApi.createCar(make_model, vin, year) : request("/cars", { method: "POST", body: { make_model, vin, year } }),
  carDetail: (carId) => USE_MOCK ? mockApi.carDetail(carId) : request(`/cars/${carId}`),
  createIssue: (carId, category, description, urgency) =>
    USE_MOCK ? mockApi.createIssue(carId, category, description, urgency) : request(`/cars/${carId}/issues`, { method: "POST", body: { category, description, urgency } }),
  recommendation: (issueId) => USE_MOCK ? mockApi.recommendation(issueId) : request(`/recommendations/${issueId}`),
  createAppointment: (car_id, date, time, service) =>
    USE_MOCK ? mockApi.createAppointment(car_id, date, time, service) : request("/appointments", { method: "POST", body: { car_id, date, time, service } }),
  getMyAppointments: () => USE_MOCK ? mockApi.getMyAppointments() : request("/appointments/my"),
  getAllAppointments: () => USE_MOCK ? mockApi.getAllAppointments() : request("/appointments/admin"),
  deleteAppointment: (id) => USE_MOCK ? mockApi.deleteAppointment(id) : request(`/appointments/${id}`, { method: "DELETE" }),
  updateAppointment: (id, date, time, service) =>
    USE_MOCK ? mockApi.updateAppointment(id, date, time, service) : request(`/appointments/${id}`, { method: "PUT", body: { date, time, service } }),
  updateAppointmentStatus: (id, status) =>
    USE_MOCK ? mockApi.updateAppointmentStatus(id, status) : request(`/appointments/${id}/status`, { method: "PUT", body: { status } }),
  getProfile: () => USE_MOCK ? mockApi.getProfile() : request("/auth/profile"),
  updateProfile: (name, email, phone) => request("/auth/profile", { method: "PUT", body: { name, email, phone } }),
  deleteCar: (carId) => request(`/cars/${carId}`, { method: "DELETE" }),
  updateCar: (carId, make_model, vin, year) =>
    request(`/cars/${carId}`, { method: "PUT", body: { make_model, vin, year } }),
};
