import React, { useState } from 'react';
import { useAuth } from './AuthContext';
import { usersApi } from '../services/api';

export default function UserProfile() {
  const { user } = useAuth();
  const [isEditing, setIsEditing] = useState(false);
  const [editData, setEditData] = useState({
    email: user?.email || '',
    phone: user?.phone || ''
  });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null);

  const handleSave = async () => {
    if (!user) return;
    
    setLoading(true);
    try {
      await usersApi.update(user.id.toString(), editData);
      setMessage({ type: 'success', text: 'Perfil atualizado com sucesso!' });
      setIsEditing(false);
      
      // Clear message after 3 seconds
      setTimeout(() => setMessage(null), 3000);
    } catch (error) {
      setMessage({ type: 'error', text: 'Erro ao atualizar perfil. Tente novamente.' });
      setTimeout(() => setMessage(null), 5000);
    } finally {
      setLoading(false);
    }
  };

  const handleCancel = () => {
    setEditData({
      email: user?.email || '',
      phone: user?.phone || ''
    });
    setIsEditing(false);
    setMessage(null);
  };

  if (!user) {
    return <div>Carregando...</div>;
  }

  return (
    <div className="max-w-4xl mx-auto">
      <div className="bg-white shadow-lg rounded-lg overflow-hidden">
        {/* Header */}
        <div className="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8">
          <div className="flex items-center">
            <div className="bg-white rounded-full h-20 w-20 flex items-center justify-center shadow-lg">
              <span className="text-blue-600 font-bold text-2xl">
                {user.name?.split(' ').map(n => n[0]).join('').slice(0, 2) || 'U'}
              </span>
            </div>
            <div className="ml-6">
              <h1 className="text-3xl font-bold text-white">{user.rank} {user.name}</h1>
              <p className="text-blue-100 text-lg">ID: {user.military_id}</p>
              <span className={`inline-block px-3 py-1 text-sm rounded-full mt-2 ${
                user.user_role === 'admin' ? 'bg-red-500 text-white' :
                user.user_role === 'commission' ? 'bg-yellow-500 text-white' :
                'bg-green-500 text-white'
              }`}>
                {user.user_role === 'admin' ? 'Administrador' :
                 user.user_role === 'commission' ? 'Membro de Comissão' : 'Usuário Padrão'}
              </span>
            </div>
          </div>
        </div>

        {/* Message */}
        {message && (
          <div className={`mx-6 mt-6 p-4 rounded-lg ${
            message.type === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'
          }`}>
            <p className={`text-sm ${message.type === 'success' ? 'text-green-700' : 'text-red-700'}`}>
              {message.text}
            </p>
          </div>
        )}

        {/* Content */}
        <div className="p-6">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {/* Informações Pessoais */}
            <div>
              <h2 className="text-xl font-semibold text-gray-900 mb-6">Informações Pessoais</h2>
              
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                  <div className="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    <span className="text-gray-900">{user.name}</span>
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Posto/Graduação</label>
                  <div className="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    <span className="text-gray-900">{user.rank}</span>
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Identificação Militar</label>
                  <div className="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    <span className="text-gray-900">{user.military_id}</span>
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  {isEditing ? (
                    <input
                      type="email"
                      value={editData.email}
                      onChange={(e) => setEditData(prev => ({ ...prev, email: e.target.value }))}
                      className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Digite seu email"
                    />
                  ) : (
                    <div className="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                      <span className="text-gray-900">{user.email || 'Não informado'}</span>
                    </div>
                  )}
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                  {isEditing ? (
                    <input
                      type="tel"
                      value={editData.phone}
                      onChange={(e) => setEditData(prev => ({ ...prev, phone: e.target.value }))}
                      className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Digite seu telefone"
                    />
                  ) : (
                    <div className="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                      <span className="text-gray-900">{user.phone || 'Não informado'}</span>
                    </div>
                  )}
                </div>
              </div>

              {/* Action Buttons */}
              <div className="mt-6 flex space-x-4">
                {isEditing ? (
                  <>
                    <button
                      onClick={handleSave}
                      disabled={loading}
                      className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
                    >
                      {loading ? 'Salvando...' : 'Salvar'}
                    </button>
                    <button
                      onClick={handleCancel}
                      className="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                      Cancelar
                    </button>
                  </>
                ) : (
                  <button
                    onClick={() => setIsEditing(true)}
                    className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    Editar Perfil
                  </button>
                )}
              </div>
            </div>

            {/* Informações do Sistema */}
            <div>
              <h2 className="text-xl font-semibold text-gray-900 mb-6">Informações do Sistema</h2>
              
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Nível de Acesso</label>
                  <div className="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    <span className={`font-medium ${
                      user.user_role === 'admin' ? 'text-red-600' :
                      user.user_role === 'commission' ? 'text-yellow-600' :
                      'text-green-600'
                    }`}>
                      {user.user_role === 'admin' ? 'Administrador' :
                       user.user_role === 'commission' ? 'Membro de Comissão' : 'Usuário Padrão'}
                    </span>
                  </div>
                </div>

                {user.user_role === 'commission' && user.commission_inventories && (
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">Inventários Permitidos</label>
                    <div className="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                      <span className="text-gray-900">
                        {user.commission_inventories.length > 0 
                          ? `IDs: ${user.commission_inventories.join(', ')}`
                          : 'Nenhum inventário atribuído'
                        }
                      </span>
                    </div>
                  </div>
                )}

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Status da Conta</label>
                  <div className="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    <span className={`font-medium ${user.is_active ? 'text-green-600' : 'text-red-600'}`}>
                      {user.is_active ? 'Ativo' : 'Inativo'}
                    </span>
                  </div>
                </div>
              </div>

              {/* System Info */}
              <div className="mt-8 p-4 bg-blue-50 rounded-lg">
                <h3 className="text-lg font-medium text-blue-900 mb-2">Sobre o Sistema</h3>
                <p className="text-blue-700 text-sm">
                  SGTI-GAC - Sistema de Gestão de TI do GAC-PAC
                </p>
                <p className="text-blue-600 text-xs mt-1">
                  Desenvolvido para otimizar o controle de ativos de TI e facilitar processos de inventário.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}