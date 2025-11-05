import React, { useState, useEffect } from 'react';
import { CustodyLog } from '../types';
import { custodyApi } from '../services/api';

interface CustodyReportsProps {
  onDataChange?: () => void;
}

interface CustodyReportSummary {
  total_custodies: number;
  active_custodies: number;
  completed_custodies: number;
  total_assets: number;
}

const CustodyReports: React.FC<CustodyReportsProps> = ({ onDataChange }) => {
  const [reportType, setReportType] = useState<'active' | 'completed' | 'all'>('active');
  const [userId, setUserId] = useState<string>('');
  const [startDate, setStartDate] = useState<string>('');
  const [endDate, setEndDate] = useState<string>('');
  const [summary, setSummary] = useState<CustodyReportSummary | null>(null);
  const [custodies, setCustodies] = useState<CustodyLog[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const loadReports = async () => {
    setLoading(true);
    setError(null);

    try {
      const params: any = { type: reportType };
      if (userId) params.user_id = userId;
      if (startDate) params.start_date = startDate;
      if (endDate) params.end_date = endDate;

      const response = await custodyApi.getReports(params);
      setSummary(response.summary);
      setCustodies(response.custodies);
    } catch (err: any) {
      setError(err.message || 'Erro ao carregar relatórios');
      console.error('Error loading custody reports:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadReports();
  }, [reportType, userId, startDate, endDate]);

  const getStatusBadge = (custody: CustodyLog) => {
    const status = custody.checkin_date ? 'Concluída' : 'Ativa';
    const colorClass = custody.checkin_date
      ? 'bg-green-100 text-green-800'
      : 'bg-blue-100 text-blue-800';

    return (
      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${colorClass}`}>
        {status}
      </span>
    );
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR');
  };

  return (
    <div className="space-y-6">
      {/* Filtros */}
      <div className="bg-white shadow rounded-lg">
        <div className="px-4 py-5 sm:p-6">
          <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
            Relatórios de Cautelas
          </h3>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Tipo de Relatório
              </label>
              <select
                value={reportType}
                onChange={(e) => setReportType(e.target.value as any)}
                className="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="active">Cautelas Ativas</option>
                <option value="completed">Cautelas Concluídas</option>
                <option value="all">Todas as Cautelas</option>
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Data Inicial
              </label>
              <input
                type="date"
                value={startDate}
                onChange={(e) => setStartDate(e.target.value)}
                className="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Data Final
              </label>
              <input
                type="date"
                value={endDate}
                onChange={(e) => setEndDate(e.target.value)}
                className="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div className="flex items-end">
              <button
                onClick={loadReports}
                disabled={loading}
                className="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50"
              >
                {loading ? 'Carregando...' : 'Gerar Relatório'}
              </button>
            </div>
          </div>

          {/* Resumo */}
          {summary && (
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
              <div className="bg-blue-50 p-4 rounded-lg">
                <div className="text-2xl font-bold text-blue-600">{summary.total_custodies}</div>
                <div className="text-sm text-blue-600">Total de Cautelas</div>
              </div>
              <div className="bg-yellow-50 p-4 rounded-lg">
                <div className="text-2xl font-bold text-yellow-600">{summary.active_custodies}</div>
                <div className="text-sm text-yellow-600">Cautelas Ativas</div>
              </div>
              <div className="bg-green-50 p-4 rounded-lg">
                <div className="text-2xl font-bold text-green-600">{summary.completed_custodies}</div>
                <div className="text-sm text-green-600">Cautelas Concluídas</div>
              </div>
              <div className="bg-purple-50 p-4 rounded-lg">
                <div className="text-2xl font-bold text-purple-600">{summary.total_assets}</div>
                <div className="text-sm text-purple-600">Ativos Emprestados</div>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* Tabela de Cautelas */}
      <div className="bg-white shadow overflow-hidden sm:rounded-md">
        <div className="px-4 py-5 sm:p-6">
          <h4 className="text-lg font-medium text-gray-900 mb-4">Detalhes das Cautelas</h4>

          {loading && (
            <div className="text-center py-4">
              <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
              <p className="mt-2 text-gray-600">Carregando relatórios...</p>
            </div>
          )}

          {error && (
            <div className="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
              <p className="text-red-800">{error}</p>
            </div>
          )}

          {!loading && !error && custodies.length === 0 && (
            <p className="text-gray-500 text-center py-4">Nenhuma cautela encontrada.</p>
          )}

          {!loading && !error && custodies.length > 0 && (
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Número
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Militar
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Data Empréstimo
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Data Devolução
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Ativos
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {custodies.map((custody) => (
                    <tr key={custody.id} className="hover:bg-gray-50">
                      <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {custody.cautela_number}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {custody.user?.name || 'N/A'}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {formatDate(custody.checkout_date)}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {custody.checkin_date ? formatDate(custody.checkin_date) : '-'}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        {getStatusBadge(custody)}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {custody.assets?.length || 0} ativo(s)
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default CustodyReports;
