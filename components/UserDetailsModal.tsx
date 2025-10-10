import React from 'react';
import { MilitaryUser, Asset, CustodyLog, AssetStatus } from '../types';

interface UserDetailsModalProps {
  user: MilitaryUser;
  assets: Asset[];
  custodyLogs: CustodyLog[];
  onViewLogDetails: (log: CustodyLog) => void;
  onClose: () => void;
}

const UserDetailsModal: React.FC<UserDetailsModalProps> = ({ user, assets, custodyLogs, onViewLogDetails, onClose }) => {
  const currentAssets = assets.filter(a => a.custodianUserId === user.id && a.status === AssetStatus.InUse);
  const userLogHistory = custodyLogs.filter(log => log.userId === user.id);

  return (
    <div
      className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
      onClick={onClose}
      role="dialog"
      aria-modal="true"
    >
      <div
        className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="flex justify-between items-start mb-6">
          <div>
            <h2 className="text-2xl font-bold text-gray-800">{user.rank} {user.name}</h2>
            <p className="text-sm text-gray-500">ID: {user.militaryId}</p>
          </div>
          <button
            onClick={onClose}
            className="-mt-2 -mr-2 text-gray-500 hover:text-gray-800"
            aria-label="Fechar"
          >
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>

        <div className="flex-grow overflow-y-auto space-y-6 pr-2">
          {/* Current Assets Section */}
          <div>
            <h3 className="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">Ativos Atualmente Cautelados</h3>
            {currentAssets.length > 0 ? (
              <table className="w-full text-sm text-left text-gray-500">
                <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                  <tr>
                    <th scope="col" className="px-4 py-2">QR Code</th>
                    <th scope="col" className="px-4 py-2">Tipo/Modelo</th>
                    <th scope="col" className="px-4 py-2">Nº de Série</th>
                  </tr>
                </thead>
                <tbody>
                  {currentAssets.map(asset => (
                    <tr key={asset.id} className="bg-white border-b hover:bg-gray-50">
                      <td className="px-4 py-2 font-mono">{asset.qrCode}</td>
                      <td className="px-4 py-2 font-medium text-gray-900">{asset.type}</td>
                      <td className="px-4 py-2">{asset.serialNumber}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            ) : (
              <p className="text-gray-500 italic px-4">Nenhum ativo cautelado no momento.</p>
            )}
          </div>

          {/* Custody History Section */}
          <div>
            <h3 className="text-xl font-semibold text-gray-700 mb-3 border-b pb-2">Histórico de Cautelas</h3>
            {userLogHistory.length > 0 ? (
              <table className="w-full text-sm text-left text-gray-500">
                <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                  <tr>
                    <th scope="col" className="px-4 py-2">Nº Cautela</th>
                    <th scope="col" className="px-4 py-2">Data Abertura</th>
                    <th scope="col" className="px-4 py-2">Data Baixa</th>
                    <th scope="col" className="px-4 py-2">Itens</th>
                    <th scope="col" className="px-4 py-2">Ação</th>
                  </tr>
                </thead>
                <tbody>
                  {userLogHistory.slice().reverse().map(log => (
                    <tr key={log.id} className="bg-white border-b hover:bg-gray-50 cursor-pointer" onClick={() => onViewLogDetails(log)}>
                      <td className="px-4 py-2 font-mono">{log.cautelaNumber}</td>
                      <td className="px-4 py-2">{new Date(log.checkoutDate).toLocaleDateString('pt-BR')}</td>
                      <td className="px-4 py-2">
                        {log.checkinDate ? new Date(log.checkinDate).toLocaleDateString('pt-BR') : <span className="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Ativa</span>}
                      </td>
                      <td className="px-4 py-2" title={log.assetIds.map(id => assets.find(a => a.id === id)?.type).join(', ')}>
                        {log.assetIds.length}
                      </td>
                      <td className="px-4 py-2">
                        <span className="font-medium text-blue-600 hover:underline">Ver Detalhes</span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            ) : (
              <p className="text-gray-500 italic px-4">Nenhum registro de cautela encontrado.</p>
            )}
          </div>
        </div>
        
        <div className="mt-8 flex justify-end">
            <button onClick={onClose} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Fechar</button>
        </div>
      </div>
    </div>
  );
};

export default UserDetailsModal;