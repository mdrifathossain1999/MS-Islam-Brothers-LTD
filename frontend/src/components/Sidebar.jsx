import React from 'react';

const Sidebar = () => {
  return (
    <nav className="sidebar-menu">
      <div className="sidebar-header">
        <h2>Home</h2>
      </div>
      <div className="sidebar-nav">
        <a href="/" className="nav-link">
          Home
        </a>
        <a href="/dashboard" className="nav-link">
          Dashboard
        </a>
        <a href="/products" className="nav-link">
          Products
        </a>
        <a href="/customers" className="nav-link">
          Customers
        </a>
        <a href="/settings" className="nav-link">
          Settings
        </a>
      </div>
    </nav>
  );
};

export default Sidebar;