
import React from 'react';

interface SidebarProps {
  setActiveView: (view: string) => void;
  activeView: string;
}

const NavItem: React.FC<{
  label: string;
  view: string;
  activeView: string;
  setActiveView: (view: string) => void;
  children: React.ReactNode;
}> = ({ label, view, activeView, setActiveView, children }) => {
  const isActive = activeView === view;
  return (
    <button
      onClick={() => setActiveView(view)}
      className={`w-full flex items-center p-3 my-1 text-sm font-medium rounded-lg transition-colors duration-200 ${
        isActive
          ? 'bg-blue-600 text-white shadow-lg'
          : 'text-gray-600 hover:bg-gray-200 hover:text-gray-800'
      }`}
    >
      {children}
      <span className="ml-3">{label}</span>
    </button>
  );
};

const Sidebar: React.FC<SidebarProps> = ({ setActiveView, activeView }) => {
  return (
    <aside className="w-64 flex-shrink-0 bg-white shadow-md p-4 flex flex-col">
      <div className="flex items-center justify-center mb-6">
        <h1 className="text-xl font-bold text-gray-800">SGAITI-UM</h1>
      </div>
      <nav className="flex-1">
        <NavItem label="Dashboard" view="dashboard" {...{ activeView, setActiveView }}>
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        </NavItem>
        <div className="my-4 border-t border-gray-200"></div>
        <h3 className="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gerenciar</h3>
        <NavItem label="Ativos" view="assets" {...{ activeView, setActiveView }}>
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </NavItem>
        <NavItem label="Setores" view="sectors" {...{ activeView, setActiveView }}>
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </NavItem>
        <NavItem label="Militares" view="users" {...{ activeView, setActiveView }}>
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </NavItem>
        <div className="my-4 border-t border-gray-200"></div>
        <h3 className="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Operações</h3>
        <NavItem label="Cautelas" view="custody" {...{ activeView, setActiveView }}>
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
        </NavItem>
        <NavItem label="Inventário" view="inventory" {...{ activeView, setActiveView }}>
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M4 6h16M4 12h16M4 18h7"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 1v4M15 1v4"></path></svg>
        </NavItem>
        <div className="my-4 border-t border-gray-200"></div>
        <h3 className="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Relatórios</h3>
        <NavItem label="Imprimir Etiquetas" view="printLabels" {...{ activeView, setActiveView }}>
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h-1m-1-6v1M6 12H5m1-6V5m6 1v-1m-6 6H5m1-1v-1M9 4v1m0 11v1m0-6v1m6-1v1m0 6v1M9 18v1m6-1v1m-6-1v1m6-6v1m-1 1h1M9 12h1m6 0h-1m-1-1v-1m-1 6v-1m-1-1h-1m-1 6v-1m-1-1h-1m6-1h-1m-1-1v-1m-1 6v-1m-1-1h-1m6 0h-1"></path></svg>
        </NavItem>
      </nav>
      <div className="mt-auto p-2 text-center text-xs text-gray-500">
        <p>&copy; 2024 SGAITI-UM</p>
        <p>Desenvolvido para excelência.</p>
      </div>
    </aside>
  );
};

export default Sidebar;
