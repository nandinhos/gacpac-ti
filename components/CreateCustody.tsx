import React, { useState, useMemo, useEffect } from 'react';
import { CustodyLog, MilitaryUser, Asset, AssetStatus } from '../types';
import { custodyApi } from '../services/api';

interface CreateCustodyProps {
  users: MilitaryUser[];
  assets: Asset[];
  onCustodyCreated: () => void;
  onBack: () => void;
}

const CreateCustody: React.FC<CreateCustodyProps> = ({ users, assets, onCustodyCreated, onBack }) => {
  const [step, setStep] = useState<'selection' | 'preview'>('selection');
  const [selectedUserId, setSelectedUserId] = useState<string>('');
  const [checkoutDate, setCheckoutDate] = useState<string>(new Date().toISOString().split('T')[0]);
  const [selectedAssetIds, setSelectedAssetIds] = useState<number[]>([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [categoryFilter, setCategoryFilter] = useState('');
  const [isCreating, setIsCreating] = useState(false);
  const [nextCautelaNumber, setNextCautelaNumber] = useState<string>('Carregando...');
  const [createdCustody, setCreatedCustody] = useState<CustodyLog | null>(null);
  const [showPrintDialog, setShowPrintDialog] = useState(false);

  const availableAssets = useMemo(() =>
    assets.filter(a => a.status === AssetStatus.Available), [assets]
  );

  useEffect(() => {
    if (step === 'preview') {
      custodyApi.getNextNumber()
        .then(data => setNextCautelaNumber(data.nextCautelaNumber))
        .catch(error => {
          console.error('Error fetching next cautela number:', error);
          setNextCautelaNumber('Erro ao carregar');
        });
    }
  }, [step]);

  const categories = useMemo(() =>
    [...new Set(availableAssets.map(a => a.category))], [availableAssets]
  );

  const filteredAssets = useMemo(() => {
    return availableAssets.filter(asset => {
      const matchesSearch = searchTerm === '' ||
        asset.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        asset.qr_code.toLowerCase().includes(searchTerm.toLowerCase()) ||
        (asset.serial_number && asset.serial_number.toLowerCase().includes(searchTerm.toLowerCase())) ||
        (asset.patrimony_id && asset.patrimony_id.toLowerCase().includes(searchTerm.toLowerCase()));

      const matchesCategory = categoryFilter === '' || asset.category === categoryFilter;

      return matchesSearch && matchesCategory;
    });
  }, [availableAssets, searchTerm, categoryFilter]);

  const handleAssetToggle = (assetId: number) => {
    setSelectedAssetIds(prev =>
      prev.includes(assetId)
        ? prev.filter(id => id !== assetId)
        : [...prev, assetId]
    );
  };

  const handleSelectAll = () => {
    const allIds = filteredAssets.map(a => a.id);
    setSelectedAssetIds(prev => {
      const allSelected = allIds.every(id => prev.includes(id));
      return allSelected ? prev.filter(id => !allIds.includes(id)) : [...prev, ...allIds.filter(id => !prev.includes(id))];
    });
  };

  const handleAdvanceToPreview = () => {
    if (!selectedUserId || !checkoutDate || selectedAssetIds.length === 0) {
      alert('Preencha todos os campos obrigatórios.');
      return;
    }
    setStep('preview');
  };

  const generatePDF = (custody: CustodyLog, user: MilitaryUser, assets: Asset[]) => {
    const jsPDF = (window as any).jsPDF;
    const doc = new jsPDF();
    const pageHeight = doc.internal.pageSize.height;

    // Header
    doc.setFontSize(18);
    doc.setFont('helvetica', 'bold');
    doc.text('TERMO DE RESPONSABILIDADE DE MATERIAL', doc.internal.pageSize.width / 2, 20, { align: 'center' });
    doc.setFontSize(14);
    doc.text(`Nº: ${custody.cautela_number}`, doc.internal.pageSize.width / 2, 28, { align: 'center' });

    // User Info
    doc.setFontSize(12);
    doc.setFont('helvetica', 'normal');
    const userInfo = `Eu, ${user.rank} ${user.name}, Idt Mil ${user.military_id}, declaro ter recebido o(s) material(is) de TI listado(s) abaixo, sob minha responsabilidade, em perfeito estado de funcionamento.`;
    const splitUserInfo = doc.splitTextToSize(userInfo, 180);
    doc.text(splitUserInfo, 15, 40);

    // Asset Table
    const tableColumn = ["QR Code", "Tipo/Modelo", "Nº de Série", "Patrimônio"];
    const tableRows: (string|undefined)[][] = assets.map(asset => [asset.qr_code, asset.name, asset.serial_number, asset.patrimony_id]);
    (doc as any).autoTable({
      head: [tableColumn],
      body: tableRows,
      startY: 60,
    });

    const finalY = (doc as any).lastAutoTable.finalY || 80;

    // Signatures
    const signatureY = pageHeight - 50 > finalY + 30 ? pageHeight - 50 : finalY + 30;
    doc.text('___________________________________________', 15, signatureY);
    doc.text(`${user.rank} ${user.name}`, 15, signatureY + 5);

    doc.text('___________________________________________', doc.internal.pageSize.width - 80, signatureY);
    doc.text('Chefe da Seção de TI', doc.internal.pageSize.width - 80, signatureY + 5);

    // Footer
    doc.setFontSize(10);
    doc.text(`Gerado em: ${new Date().toLocaleString('pt-BR')}`, 15, pageHeight - 10);

    doc.save(`termo_cautela_${custody.cautela_number.replace(/\//g, '-')}.pdf`);
  };

  const handlePrint = () => {
    if (createdCustody && selectedUser) {
      generatePDF(createdCustody, selectedUser, selectedAssets);
    }
    onCustodyCreated();
    onBack();
  };

  const handleClose = () => {
    onCustodyCreated();
    onBack();
  };

  const handleConfirmCreate = async () => {
    setIsCreating(true);
    try {
      const user = users.find(u => u.id.toString() === selectedUserId);
      if (!user) throw new Error('Usuário não encontrado');

      const createdLog = await custodyApi.create({
        user_id: user.id,
        checkout_date: checkoutDate,
        assetIds: selectedAssetIds,
      });

      setCreatedCustody(createdLog);
      setShowPrintDialog(true);
    } catch (error: any) {
      console.error('Error creating custody:', error);
      alert('Erro ao criar cautela: ' + error.message);
    } finally {
      setIsCreating(false);
    }
  };

  const selectedUser = users.find(u => u.id.toString() === selectedUserId);
  const selectedAssets = availableAssets.filter(a => selectedAssetIds.includes(a.id));

  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold text-gray-800">Criar Nova Cautela</h1>
        <button
          onClick={onBack}
          className="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition"
        >
          Voltar
        </button>
      </div>

      {step === 'selection' && (
        <>
          {/* Seleção do Usuário e Data */}
          <div className="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 className="text-xl font-semibold mb-4">1. Informações Básicas</h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Militar Responsável
                </label>
                <select
                  value={selectedUserId}
                  onChange={(e) => setSelectedUserId(e.target.value)}
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                  required
                >
                  <option value="">Selecione um militar</option>
                  {users.filter(u => u.is_active).map(u => (
                    <option key={u.id} value={u.id.toString()}>
                      {u.rank} {u.name}
                    </option>
                  ))}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Data de Saída
                </label>
                <input
                  type="date"
                  value={checkoutDate}
                  onChange={(e) => setCheckoutDate(e.target.value)}
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                  required
                />
              </div>
            </div>
          </div>

      {/* Seleção de Ativos */}
      <div className="bg-white p-6 rounded-lg shadow-md">
        <h2 className="text-xl font-semibold mb-4">2. Selecione os Ativos Disponíveis</h2>

        {/* Filtros */}
        <div className="flex flex-wrap gap-4 mb-4">
          <div className="flex-1 min-w-64">
            <input
              type="text"
              placeholder="Buscar por nome, QR Code, série ou patrimônio..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full px-4 py-2 border border-gray-300 rounded-md"
            />
          </div>
          <select
            value={categoryFilter}
            onChange={(e) => setCategoryFilter(e.target.value)}
            className="px-4 py-2 border border-gray-300 rounded-md"
          >
            <option value="">Todas as categorias</option>
            {categories.map(cat => (
              <option key={cat} value={cat}>{cat}</option>
            ))}
          </select>
          <button
            onClick={handleSelectAll}
            className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          >
            {filteredAssets.every(a => selectedAssetIds.includes(a.id)) ? 'Desmarcar Todos' : 'Selecionar Todos'}
          </button>
        </div>

        {/* Lista de Ativos */}
        <div className="max-h-96 overflow-y-auto border rounded-md">
          <table className="w-full text-sm">
            <thead className="bg-gray-50 sticky top-0">
              <tr>
                <th className="px-4 py-2 text-left">
                  <input
                    type="checkbox"
                    checked={filteredAssets.length > 0 && filteredAssets.every(a => selectedAssetIds.includes(a.id))}
                    onChange={handleSelectAll}
                  />
                </th>
                <th className="px-4 py-2 text-left">QR Code</th>
                <th className="px-4 py-2 text-left">Nome</th>
                <th className="px-4 py-2 text-left">Categoria</th>
                <th className="px-4 py-2 text-left">Patrimônio</th>
                <th className="px-4 py-2 text-left">Nº Série</th>
              </tr>
            </thead>
            <tbody>
              {filteredAssets.map(asset => (
                <tr key={asset.id} className="border-t hover:bg-gray-50">
                  <td className="px-4 py-2">
                    <input
                      type="checkbox"
                      checked={selectedAssetIds.includes(asset.id)}
                      onChange={() => handleAssetToggle(asset.id)}
                    />
                  </td>
                  <td className="px-4 py-2 font-mono">{asset.qr_code}</td>
                  <td className="px-4 py-2 font-medium">{asset.name}</td>
                  <td className="px-4 py-2">{asset.category}</td>
                  <td className="px-4 py-2">{asset.patrimony_id || '-'}</td>
                  <td className="px-4 py-2">{asset.serial_number || '-'}</td>
                </tr>
              ))}
            </tbody>
          </table>
          {filteredAssets.length === 0 && (
            <div className="p-8 text-center text-gray-500">
              Nenhum ativo disponível encontrado.
            </div>
          )}
        </div>

        <div className="mt-4 text-sm text-gray-600">
        {selectedAssetIds.length} ativo(s) selecionado(s) de {availableAssets.length} disponível(is)
        </div>
        </div>

        {/* Botão Avançar */}
        <div className="mt-6 flex justify-end">
        <button
        onClick={handleAdvanceToPreview}
        disabled={!selectedUserId || !checkoutDate || selectedAssetIds.length === 0}
        className="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition"
        >
        Avançar para Preview
        </button>
        </div>
            </>
            )}

      {step === 'preview' && (
        <>
          <div className="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 className="text-xl font-semibold mb-4">Preview da Cautela</h2>
            <div className="space-y-4">
              <div>
                <strong>Número da Cautela:</strong> {nextCautelaNumber}
              </div>
              <div>
                <strong>Militar Responsável:</strong> {selectedUser ? `${selectedUser.rank} ${selectedUser.name}` : ''}
              </div>
              <div>
                <strong>Data de Saída:</strong> {new Date(checkoutDate).toLocaleDateString('pt-BR')}
              </div>
              <div>
                <strong>Ativos Selecionados ({selectedAssets.length}):</strong>
                <ul className="mt-2 space-y-1">
                  {selectedAssets.map(asset => (
                    <li key={asset.id} className="text-sm">
                      • {asset.name} (QR: {asset.qr_code})
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>

          <div className="flex justify-between">
            <button
              onClick={() => setStep('selection')}
              className="px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition"
            >
              Voltar para Edição
            </button>
            <button
              onClick={handleConfirmCreate}
              disabled={isCreating || nextCautelaNumber === 'Carregando...' || nextCautelaNumber === 'Erro ao carregar'}
              className="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition"
            >
              {isCreating ? 'Criando...' : 'Confirmar e Criar Cautela'}
            </button>
          </div>
        </>
      )}

      {showPrintDialog && createdCustody && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
            <h3 className="text-lg font-semibold mb-4">Cautela Criada com Sucesso!</h3>
            <p className="mb-4">
              A cautela <strong>{createdCustody.cautela_number}</strong> foi criada.
            </p>
            <p className="mb-6">Deseja exportar o termo de responsabilidade para PDF?</p>
            <div className="flex justify-end space-x-3">
              <button
                onClick={handlePrint}
                className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
              >
                Exportar Termo
              </button>
              <button
                onClick={handleClose}
                className="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition"
              >
                Fechar
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default CreateCustody;
