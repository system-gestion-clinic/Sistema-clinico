import { Outlet } from "react-router-dom";
import { Topbar } from "./Topbar";
import { Sidebar } from "./Sidebar";

export function Layout() {
  return (
    <>
      <Topbar />
      <div className="container">
        <div className="layout">
          <Sidebar />
          <Outlet />
        </div>
      </div>
    </>
  );
}
