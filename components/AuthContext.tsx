import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { AuthContextType, AuthUser, LoginCredentials, AuthResponse } from '../types';

const AuthContext = createContext<AuthContextType | undefined>(undefined);

interface AuthProviderProps {
  children: ReactNode;
}

export function AuthProvider({ children }: AuthProviderProps) {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [abilities, setAbilities] = useState<string[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Verificar se há token salvo no localStorage
    const savedToken = localStorage.getItem('auth_token');
    const savedUser = localStorage.getItem('auth_user');
    const savedAbilities = localStorage.getItem('auth_abilities');

    if (savedToken && savedUser && savedAbilities) {
      setToken(savedToken);
      setUser(JSON.parse(savedUser));
      setAbilities(JSON.parse(savedAbilities));
    }
    
    setLoading(false);
  }, []);

  const login = async (credentials: LoginCredentials): Promise<void> => {
    try {
      const response = await fetch('http://localhost:5050/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(credentials),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Erro ao fazer login');
      }

      const data: AuthResponse = await response.json();
      
      // Salvar no estado
      setUser(data.user);
      setToken(data.token);
      setAbilities(data.abilities);

      // Salvar no localStorage
      localStorage.setItem('auth_token', data.token);
      localStorage.setItem('auth_user', JSON.stringify(data.user));
      localStorage.setItem('auth_abilities', JSON.stringify(data.abilities));

    } catch (error) {
      console.error('Erro no login:', error);
      throw error;
    }
  };

  const logout = (): void => {
    // Limpar estado
    setUser(null);
    setToken(null);
    setAbilities([]);

    // Limpar localStorage
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    localStorage.removeItem('auth_abilities');

    // Opcional: fazer chamada para API de logout
    if (token) {
      fetch('http://localhost:5050/api/logout', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      }).catch(console.error);
    }
  };

  const isAuthenticated = !!user && !!token;

  const hasAbility = (ability: string): boolean => {
    return abilities.includes(ability) || abilities.includes('view:all');
  };

  const contextValue: AuthContextType = {
    user,
    token,
    abilities,
    login,
    logout,
    isAuthenticated,
    hasAbility,
    loading,
  };

  return (
    <AuthContext.Provider value={contextValue}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth(): AuthContextType {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
}