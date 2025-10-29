import React, { useState, useMemo } from 'react';
import { MilitaryUser, Sector, Asset, CustodyLog, AssetStatus } from '../types';
import UserDetailsModal from './UserDetailsModal';
import CustodyDetailsModal from './CustodyDetailsModal';

const UserForm: React.FC<{
  user: Partial<MilitaryUser> | null;
  sectors: Sector[];
  onSave: (user: MilitaryUser) => void;
  onCancel: () => void;
  lastUserId: string;
}> = ({ user, sectors, onSave, onCancel, lastUserId }) => {
  const [formData, setFormData] = useState<Partial<MilitaryUser>>(
    user || { name: '', rank: '', military_id: '', sector_id: sectors[0]?.id, is_active: true }
  );

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value, type } = e.target;
    if (type === 'checkbox') {
        const checked = (e.target as HTMLInputElement).checked;
        setFormData(prev => ({ ...prev, [name]: checked }));
    } else {
        setFormData(prev => ({ ...prev, [name]: value }));
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.name || !formData.rank || !formData.military_id) return;
    const newUser: MilitaryUser = {
      id: formData.id || `temp-${Date.now()}`,
      name: formData.name,
      rank: formData.rank,
      military_id: formData.military_id,
      sector_id: formData.sector_id,
      is_active: formData.is_active !== undefined ? formData.is_active : true,
    };
    onSave(newUser);
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg">
        <h2 className="text-2xl font-bold mb-6 text-gray-800">
          {formData.id ? 'Editar Militar' : 'Adicionar Novo Militar'}
        </h2>
        <form onSubmit={handleSubmit}>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="col-span-2">
              <label className="block text-sm font-medium text-gray-700">Nome Completo</label>
              <input type="text" name="name" value={formData.name || ''} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Posto/Graduação</label>
              <input type="text" name="rank" value={formData.rank || ''} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Identidade Militar/CPF</label>
              <input type="text" name="militaryId" value={formData.militaryId || ''} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
            </div>
            <div className="col-span-2">
              <label className="block text-sm font-medium text-gray-700">Setor de Lotação</label>
              <select name="sectorId" value={formData.sectorId} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                {sectors.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
              </select>
            </div>
            <div className="col-span-2 flex items-center">
                <input type="checkbox" id="is_active" name="is_active" checked={formData.is_active} onChange={handleChange} className="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                <label htmlFor="is_active" className="ml-2 block text-sm text-gray-900">Militar Ativo</label>
            </div>
          </div>
          <div className="mt-8 flex justify-end space-x-4">
            <button type="button" onClick={onCancel} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancelar</button>
            <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  );
};

const UserManagement: React.FC<{
  users: MilitaryUser[],
  setUsers: React.Dispatch<React.SetStateAction<MilitaryUser[]>>,
  sectors: Sector[],
  assets: Asset[],
  setAssets: React.Dispatch<React.SetStateAction<Asset[]>>,
  custodyLogs: CustodyLog[],
  setCustodyLogs: React.Dispatch<React.SetStateAction<CustodyLog[]>>
}> = ({ users, setUsers, sectors, assets, setAssets, custodyLogs, setCustodyLogs }) => {
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingUser, setEditingUser] = useState<Partial<MilitaryUser> | null>(null);
  const [viewingUser, setViewingUser] = useState<MilitaryUser | null>(null);
  const [viewingLog, setViewingLog] = useState<CustodyLog | null>(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState('all'); // 'all', 'active', 'inactive'
  const [sectorFilter, setSectorFilter] = useState('all'); // 'all' or a sector ID

  const handleAdd = () => {
    setEditingUser(null);
    setIsFormOpen(true);
  };

  const handleEdit = (user: MilitaryUser) => {
    setEditingUser(user);
    setIsFormOpen(true);
  };

  const handleDelete = (id: string) => {
    if (window.confirm('Tem certeza que deseja excluir este militar?')) {
      setUsers(users.filter(u => u.id !== id));
    }
  };

  const handleSave = (user: MilitaryUser) => {
    if (editingUser && editingUser.id) {
      setUsers(users.map(u => u.id === user.id ? user : u));
    } else {
      setUsers([...users, user]);
    }
    setIsFormOpen(false);
    setEditingUser(null);
  };
  
  const lastUserId = useMemo(() => users.reduce((max, u) => u.id > max ? u.id : max, '0'), [users]);

  const filteredUsers = useMemo(() => {
    let filtered = users;

    // Apply status filter
    if (statusFilter !== 'all') {
      const isActive = statusFilter === 'active';
      filtered = filtered.filter(user => user.is_active === isActive);
    }

    // Apply sector filter
    if (sectorFilter !== 'all') {
      filtered = filtered.filter(user => user.sector_id === sectorFilter);
    }

    // Apply text search
    if (searchTerm) {
      const term = searchTerm.toLowerCase();
      filtered = filtered.filter(user => 
        user.name.toLowerCase().includes(term) ||
        user.rank.toLowerCase().includes(term) ||
        user.military_id.toLowerCase().includes(term)
      );
    }

    return filtered;
  }, [users, searchTerm, statusFilter, sectorFilter]);

  // Handlers for CustodyDetailsModal, adapted from CustodyManagement
  const handleDischarge = (logId: string) => {
    const logToDischarge = custodyLogs.find(l => l.id === logId);
    if (!logToDischarge) return;

    if (window.confirm('Tem certeza que deseja dar baixa nesta cautela? Os ativos serão retornados ao almoxarifado.')) {
        setCustodyLogs(prev => prev.map(log => log.id === logId ? { ...log, checkin_date: new Date().toISOString() } : log));
        
        const updatedAssets = assets.map(asset => {
            if (logToDischarge.assetIds?.includes(asset.id)) {
                return { ...asset, status: AssetStatus.Available, custodian_user_id: undefined };
            }
            return asset;
        });
        setAssets(updatedAssets);

        // Also update the log being viewed in the modal
        setViewingLog(prev => prev ? { ...prev, checkin_date: new Date().toISOString() } : null);
    }
  };

  const handleUploadSignedTerm = (logId: string, fileUrl: string) => {
    setCustodyLogs(prev => prev.map(log => log.id === logId ? { ...log, signed_term_url: fileUrl } : log));
    setViewingLog(prev => prev ? { ...prev, signed_term_url: fileUrl } : null);
  };

  const userForLog = viewingLog ? users.find(u => u.id === viewingLog.user_id) : null;

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold text-gray-800">Gestão de Militares</h1>
        <button onClick={handleAdd} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Adicionar Militar
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg border">
        <div className="col-span-1 md:col-span-3">
            <label className="block text-sm font-medium text-gray-700 mb-1">Busca Rápida</label>
            <input 
            type="text"
            placeholder="Buscar por Nome, Posto/Graduação ou Identidade..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
        </div>
        <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Filtrar por Status</label>
            <select value={statusFilter} onChange={e => setStatusFilter(e.target.value)} className="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">Todos</option>
                <option value="active">Ativo</option>
                <option value="inactive">Inativo</option>
            </select>
        </div>
        <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Filtrar por Setor</label>
            <select value={sectorFilter} onChange={e => setSectorFilter(e.target.value)} className="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">Todos os Setores</option>
                {sectors.map(sector => (
                    <option key={sector.id} value={sector.id}>{sector.name}</option>
                ))}
            </select>
        </div>
      </div>

      <div className="bg-white shadow-md rounded-lg overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left text-gray-500">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" className="px-6 py-3">Nome Completo</th>
                <th scope="col" className="px-6 py-3">Posto/Grad</th>
                <th scope="col" className="px-6 py-3">Setor</th>
                <th scope="col" className="px-6 py-3">Status</th>
                <th scope="col" className="px-6 py-3 text-right">Ações</th>
              </tr>
            </thead>
            <tbody>
              {filteredUsers.map(user => {
                const sectorName = user.sector_name || 'N/A';
                return (
                  <tr key={user.id} className="bg-white border-b hover:bg-gray-50">
                    <td className="px-6 py-4 font-medium text-gray-900">{user.name}</td>
                    <td className="px-6 py-4">{user.rank}</td>
                    <td className="px-6 py-4">{sectorName}</td>
                    <td className="px-6 py-4">
                      <span className={`px-2 py-1 text-xs font-medium rounded-full ${user.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`}>
                        {user.is_active ? 'Ativo' : 'Inativo'}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end space-x-1">
                          <button
                              onClick={() => setViewingUser(user)}
                              className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-blue-600 focus:outline-none"
                              title="Ver Detalhes"
                          >
                              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                          </button>
                          <button 
                              onClick={() => handleEdit(user)}
                              className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-blue-600 focus:outline-none"
                              title="Editar Militar"
                          >
                              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.586a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                          </button>
                          <button 
                              onClick={() => handleDelete(user.id)}
                              className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-red-600 focus:outline-none"
                              title="Excluir Militar"
                          >
                              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                          </button>
                      </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      {isFormOpen && (
        <UserForm
          user={editingUser}
          sectors={sectors}
          onSave={handleSave}
          onCancel={() => setIsFormOpen(false)}
          lastUserId={lastUserId}
        />
      )}

      {viewingUser && (
        <UserDetailsModal
            user={viewingUser}
            assets={assets}
            custodyLogs={custodyLogs}
            onViewLogDetails={(log) => setViewingLog(log)}
            onClose={() => setViewingUser(null)}
        />
      )}

      {viewingLog && userForLog && (
        <CustodyDetailsModal
          log={viewingLog}
          user={userForLog}
          assetsInLog={assets.filter(a => viewingLog.assetIds.includes(a.id))}
          onClose={() => setViewingLog(null)}
          onDischarge={handleDischarge}
          onUploadSignedTerm={handleUploadSignedTerm}
        />
      )}
    </div>
  );
};

export default UserManagement;