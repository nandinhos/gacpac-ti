
import React, { useState } from 'react';
import { useAuth } from './AuthContext';
import ConfirmationModal from './ConfirmationModal';

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
  const { user, logout, hasAbility } = useAuth();
  const [showLogoutModal, setShowLogoutModal] = useState(false);

  const handleLogout = () => {
    setShowLogoutModal(true);
  };

  const handleConfirmLogout = async () => {
    logout();
  };

  return (
    <aside className="w-64 flex-shrink-0 bg-white shadow-md p-4 flex flex-col">
      <div className="text-center mb-6">
        <h1 className="text-xl font-bold text-gray-800">SGTI-GAC</h1>
        <p className="text-xs text-gray-500 mt-1">Sistema de Gestão de TI</p>
      </div>

      {/* User Profile Section */}
      <div className="bg-blue-50 rounded-lg p-3 mb-6">
        <div className="flex items-center">
          <div className="bg-blue-600 rounded-full h-10 w-10 flex items-center justify-center">
            <span className="text-white font-bold text-sm">
              {user?.name?.split(' ').map(n => n[0]).join('').slice(0, 2) || 'U'}
            </span>
          </div>
          <div className="ml-3 flex-1 min-w-0">
            <p className="text-sm font-medium text-gray-900 truncate">
              {user?.rank} {user?.name}
            </p>
            <p className="text-xs text-gray-500 truncate">
              {user?.military_id}
            </p>
            <span className={`inline-block px-2 py-1 text-xs rounded-full mt-1 ${
              user?.user_role === 'admin' ? 'bg-red-100 text-red-800' :
              user?.user_role === 'commission' ? 'bg-yellow-100 text-yellow-800' :
              'bg-green-100 text-green-800'
            }`}>
              {user?.user_role === 'admin' ? 'Admin' :
               user?.user_role === 'commission' ? 'Comissão' : 'Usuário'}
            </span>
          </div>
        </div>
      </div>
      <nav className="flex-1">
        <NavItem label="Dashboard" view="dashboard" {...{ activeView, setActiveView }}>
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        </NavItem>

        {/* Admin/Commission Only Sections */}
        {hasAbility('edit:all') && (
          <>
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
          </>
        )}

        <div className="my-4 border-t border-gray-200"></div>
        <h3 className="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Operações</h3>
        
        {/* Custody - All authenticated users can view */}
        {hasAbility('view:custody') && (
          <NavItem label="Cautelas" view="custody" {...{ activeView, setActiveView }}>
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
          </NavItem>
        )}

        {/* Inventory - Commission and Admin only */}
        {hasAbility('view:inventory') && (
          <NavItem label="Inventário" view="inventory" {...{ activeView, setActiveView }}>
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M4 6h16M4 12h16M4 18h7"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 1v4M15 1v4"></path></svg>
          </NavItem>
        )}

        {/* Reports - Admin only */}
        {hasAbility('edit:all') && (
          <>
            <div className="my-4 border-t border-gray-200"></div>
            <h3 className="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Relatórios</h3>
            <NavItem label="Imprimir Etiquetas" view="printLabels" {...{ activeView, setActiveView }}>
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h-1m-1-6v1M6 12H5m1-6V5m6 1v-1m-6 6H5m1-1v-1M9 4v1m0 11v1m0-6v1m6-1v1m0 6v1M9 18v1m6-1v1m-6-1v1m6-6v1m-1 1h1M9 12h1m6 0h-1m-1-1v-1m-1 6v-1m-1-1h-1m-1 6v-1m-1-1h-1m6-1h-1m-1-1v-1m-1 6v-1m-1-1h-1m6 0h-1"></path></svg>
            </NavItem>
          </>
        )}

        {/* User Profile - All users */}
        <div className="my-4 border-t border-gray-200"></div>
        <h3 className="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Conta</h3>
        <NavItem label="Meu Perfil" view="profile" {...{ activeView, setActiveView }}>
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </NavItem>
      </nav>
      {/* Logout Button */}
      <div className="mt-auto">
        <button
          onClick={handleLogout}
          className="w-full flex items-center p-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          <span className="ml-3">Sair</span>
        </button>
        
        <div className="mt-4 p-2 text-center text-xs text-gray-500">
          <p>&copy; 2024 SGTI-GAC</p>
          <p>Desenvolvido para excelência.</p>
        </div>
      </div>

      {/* Modal de Confirmação de Logout */}
      <ConfirmationModal
        isOpen={showLogoutModal}
        onClose={() => setShowLogoutModal(false)}
        onConfirm={handleConfirmLogout}
        title="Sair do Sistema"
        message="Tem certeza que deseja sair do sistema? Você precisará fazer login novamente para acessar."
        confirmText="Sair"
        cancelText="Cancelar"
        type="warning"
        icon={
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
        }
      />
    </aside>
  );
};

export default Sidebar;
