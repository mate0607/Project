import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import App from "./App.jsx";
import LocationsApp from "./LocationsApp.jsx";
import ServiceApp from "./ServiceApp.jsx";

function RootApp() {
  return (
    <Routes>
      <Route path="/*" element={<App />} />
      <Route path="/locations/*" element={<LocationsApp />} />
      <Route path="/service/*" element={<ServiceApp />} />
    </Routes>
  );
}

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <BrowserRouter>
      <RootApp />
    </BrowserRouter>
  </React.StrictMode>
);