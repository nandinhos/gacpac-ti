import React from 'react';
import { useAuth } from './AuthContext';

interface ProtectedRouteProps {
  children: React.ReactNode;
  requiredAbility?: string;
  requiredRole?: 'user' | 'commission' | 'admin';
  fallback?: React.ReactNode;
}

export default function ProtectedRoute({
  children,
  requiredAbility,
  requiredRole,
  fallback
}: ProtectedRouteProps) {
  const { user, hasAbility } = useAuth();

  // Verificar se tem a habilidade necessária
  if (requiredAbility && !hasAbility(requiredAbility)) {
    return (
      fallback || (
        <div className="text-center py-12">
          <div className="mx-auto h-12 w-12 text-gray-400 mb-4">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <h3 className="text-lg font-medium text-gray-900 mb-2">Acesso Restrito</h3>
          <p className="text-gray-500">Você não tem permissão para acessar esta funcionalidade.</p>
        </div>
      )
    );
  }

  // Verificar se tem o role necessário
  if (requiredRole && user?.user_role !== requiredRole && user?.user_role !== 'admin') {
    return (
      fallback || (
        <div className="text-center py-12">
          <div className="mx-auto h-12 w-12 text-gray-400 mb-4">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <h3 className="text-lg font-medium text-gray-900 mb-2">Acesso Restrito</h3>
          <p className="text-gray-500">Esta funcionalidade é exclusiva para {requiredRole === 'commission' ? 'membros de comissão' : 'administradores'}.</p>
        </div>
      )
    );
  }

  return <>{children}</>;
}