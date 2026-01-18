// Mock API mód teszteléshez - MySQL nélkül
const MOCK_MODE = false; // Ezt ki lehet kapcsolni ha van backend
const MOCK_USERS = JSON.parse(localStorage.getItem("mockUsers")) || [];
const MOCK_CARS = JSON.parse(localStorage.getItem("mockCars")) || [];
const MOCK_APPOINTMENTS = JSON.parse(localStorage.getItem("mockAppointments")) || [];

function saveMockData() {
  localStorage.setItem("mockUsers", JSON.stringify(MOCK_USERS));
  localStorage.setItem("mockCars", JSON.stringify(MOCK_CARS));
  localStorage.setItem("mockAppointments", JSON.stringify(MOCK_APPOINTMENTS));
}

export const mockApi = {
  register: (name, email, password) => {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        if (MOCK_USERS.find(u => u.email === email)) {
          reject(new Error("Ez az email már regisztrálva van"));
        } else {
          const newUser = { id: Date.now(), name, email, password };
          MOCK_USERS.push(newUser);
          saveMockData();
          resolve({ token: "mock_token_" + newUser.id, user: newUser });
        }
      }, 500); // 500ms delay szimuláláshoz
    });
  },

  login: (email, password) => {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        const user = MOCK_USERS.find(u => u.email === email && u.password === password);
        if (user) {
          resolve({ token: "mock_token_" + user.id, user });
        } else {
          reject(new Error("Hibás email vagy jelszó"));
        }
      }, 500);
    });
  },

  getProfile: () => {
    return new Promise((resolve) => {
      setTimeout(() => {
        const token = localStorage.getItem("token");
        const userId = parseInt(token?.split("_")[2]);
        const user = MOCK_USERS.find(u => u.id === userId);
        resolve(user || {});
      }, 300);
    });
  },

  cars: () => {
    return new Promise((resolve) => {
      setTimeout(() => {
        const token = localStorage.getItem("token");
        const userId = parseInt(token?.split("_")[2]);
        resolve({ cars: MOCK_CARS.filter(c => c.user_id === userId) });
      }, 300);
    });
  },

  createCar: (make_model, vin, year) => {
    return new Promise((resolve) => {
      setTimeout(() => {
        const token = localStorage.getItem("token");
        const userId = parseInt(token?.split("_")[2]);
        const newCar = { id: Date.now(), user_id: userId, make_model, vin, year };
        MOCK_CARS.push(newCar);
        saveMockData();
        resolve(newCar);
      }, 300);
    });
  },

  carDetail: (carId) => {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve(MOCK_CARS.find(c => c.id == carId) || {});
      }, 300);
    });
  },

  createIssue: (carId, category, description, urgency) => {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({ id: Date.now(), car_id: carId, category, description, urgency });
      }, 300);
    });
  },

  recommendation: (issueId) => {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({ id: issueId, recommendation: "Mock ajánlás - csere szükséges" });
      }, 300);
    });
  },

  createAppointment: (car_id, date, time, service) => {
    return new Promise((resolve) => {
      setTimeout(() => {
        const newAppointment = { id: Date.now(), car_id, date, time, service, status: "pending" };
        MOCK_APPOINTMENTS.push(newAppointment);
        saveMockData();
        resolve(newAppointment);
      }, 300);
    });
  },

  getMyAppointments: () => {
    return new Promise((resolve) => {
      setTimeout(() => {
        const token = localStorage.getItem("token");
        const userId = parseInt(token?.split("_")[2]);
        const userCars = MOCK_CARS.filter(c => c.user_id === userId).map(c => c.id);
        resolve(MOCK_APPOINTMENTS.filter(a => userCars.includes(a.car_id)));
      }, 300);
    });
  },

  getAllAppointments: () => {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve(MOCK_APPOINTMENTS);
      }, 300);
    });
  },

  deleteAppointment: (id) => {
    return new Promise((resolve) => {
      setTimeout(() => {
        const index = MOCK_APPOINTMENTS.findIndex(a => a.id == id);
        if (index > -1) MOCK_APPOINTMENTS.splice(index, 1);
        saveMockData();
        resolve({ success: true });
      }, 300);
    });
  },

  updateAppointment: (id, date, time, service) => {
    return new Promise((resolve) => {
      setTimeout(() => {
        const apt = MOCK_APPOINTMENTS.find(a => a.id == id);
        if (apt) {
          apt.date = date;
          apt.time = time;
          apt.service = service;
          saveMockData();
        }
        resolve(apt);
      }, 300);
    });
  },

  updateAppointmentStatus: (id, status) => {
    return new Promise((resolve) => {
      setTimeout(() => {
        const apt = MOCK_APPOINTMENTS.find(a => a.id == id);
        if (apt) apt.status = status;
        saveMockData();
        resolve(apt);
      }, 300);
    });
  },

  health: () => Promise.resolve({ status: "ok" })
};

export default mockApi;
