import React, { useState, useMemo, useEffect } from 'react';

const AssetSelectorAdvanced = ({ availableAssets, selectedAssetIds, onSelectionChange }) => {
    const [searchTerm, setSearchTerm] = useState('');
    const [selectAll, setSelectAll] = useState(false);
    const [showAvailable, setShowAvailable] = useState(true);
    const [showSelected, setShowSelected] = useState(false);

    // Filtrar ativos disponíveis baseado na busca
    const filteredAvailableAssets = useMemo(() => {
        if (!searchTerm) return availableAssets;
        
        const search = searchTerm.toLowerCase();
        return availableAssets.filter(asset => 
            asset.qr_code.toLowerCase().includes(search) ||
            asset.name.toLowerCase().includes(search) ||
            asset.serial_number?.toLowerCase().includes(search) ||
            asset.sector?.name?.toLowerCase().includes(search)
        );
    }, [availableAssets, searchTerm]);

    // Ativos selecionados
    const selectedAssets = useMemo(() => 
        availableAssets.filter(asset => selectedAssetIds.includes(asset.id)),
        [availableAssets, selectedAssetIds]
    );

    // Verificar se todos os ativos filtrados estão selecionados
    useEffect(() => {
        if (filteredAvailableAssets.length > 0) {
            const allSelected = filteredAvailableAssets.every(asset => 
                selectedAssetIds.includes(asset.id)
            );
            setSelectAll(allSelected);
        }
    }, [filteredAvailableAssets, selectedAssetIds]);

    // Toggle seleção individual
    const handleToggleAsset = (assetId) => {
        const newSelection = selectedAssetIds.includes(assetId)
            ? selectedAssetIds.filter(id => id !== assetId)
            : [...selectedAssetIds, assetId];
        
        onSelectionChange(newSelection);
    };

    // Toggle seleção em massa
    const handleToggleAll = () => {
        if (selectAll) {
            // Remover todos os ativos filtrados da seleção
            const newSelection = selectedAssetIds.filter(id => 
                !filteredAvailableAssets.some(asset => asset.id === id)
            );
            onSelectionChange(newSelection);
        } else {
            // Adicionar todos os ativos filtrados à seleção
            const newIds = filteredAvailableAssets
                .filter(asset => !selectedAssetIds.includes(asset.id))
                .map(asset => asset.id);
            onSelectionChange([...selectedAssetIds, ...newIds]);
        }
    };

    // Remover ativo selecionado
    const handleRemoveSelected = (assetId) => {
        const newSelection = selectedAssetIds.filter(id => id !== assetId);
        onSelectionChange(newSelection);
    };

    // Limpar todas as seleções
    const handleClearAll = () => {
        onSelectionChange([]);
    };

    return (
        <div className="space-y-4">
            {/* Header com estatísticas */}
            <div className="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div className="flex items-center space-x-6">
                        <div className="text-sm">
                            <span className="font-medium text-blue-900">Disponíveis:</span>
                            <span className="ml-1 text-blue-700">{availableAssets.length}</span>
                        </div>
                        <div className="text-sm">
                            <span className="font-medium text-green-900">Selecionados:</span>
                            <span className="ml-1 text-green-700">{selectedAssets.length}</span>
                        </div>
                        <div className="text-sm">
                            <span className="font-medium text-purple-900">Filtrados:</span>
                            <span className="ml-1 text-purple-700">{filteredAvailableAssets.length}</span>
                        </div>
                    </div>
                    
                    {selectedAssets.length > 0 && (
                        <button
                            type="button"
                            onClick={handleClearAll}
                            className="text-sm px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors"
                        >
                            Limpar Seleção
                        </button>
                    )}
                </div>
            </div>

            {/* Busca */}
            <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg className="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    placeholder="Buscar por QR Code, nome, série ou setor..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                />
                {searchTerm && (
                    <button
                        type="button"
                        onClick={() => setSearchTerm('')}
                        className="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                        <svg className="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                )}
            </div>

            {/* Tabs */}
            <div className="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                <button
                    type="button"
                    onClick={() => { setShowAvailable(true); setShowSelected(false); }}
                    className={`flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors ${
                        showAvailable 
                            ? 'bg-white text-blue-700 shadow-sm' 
                            : 'text-gray-500 hover:text-gray-700'
                    }`}
                >
                    Ativos Disponíveis ({filteredAvailableAssets.length})
                </button>
                <button
                    type="button"
                    onClick={() => { setShowAvailable(false); setShowSelected(true); }}
                    className={`flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors ${
                        showSelected 
                            ? 'bg-white text-green-700 shadow-sm' 
                            : 'text-gray-500 hover:text-gray-700'
                    }`}
                >
                    Selecionados ({selectedAssets.length})
                </button>
            </div>

            {/* Conteúdo das tabs */}
            {showAvailable && (
                <div className="border border-gray-200 rounded-lg">
                    {/* Header da lista com seleção em massa */}
                    {filteredAvailableAssets.length > 0 && (
                        <div className="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                            <label className="flex items-center space-x-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={selectAll}
                                    onChange={handleToggleAll}
                                    className="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                />
                                <span className="text-sm font-medium text-gray-700">
                                    Selecionar todos ({filteredAvailableAssets.length})
                                </span>
                            </label>
                            
                            <div className="text-xs text-gray-500">
                                {filteredAvailableAssets.filter(asset => selectedAssetIds.includes(asset.id)).length} selecionados
                            </div>
                        </div>
                    )}

                    {/* Lista de ativos */}
                    <div className="max-h-96 overflow-y-auto">
                        {filteredAvailableAssets.length === 0 ? (
                            <div className="p-8 text-center text-gray-500">
                                {searchTerm ? (
                                    <div>
                                        <svg className="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <p>Nenhum ativo encontrado para "{searchTerm}"</p>
                                        <p className="text-sm mt-1">Tente termos diferentes ou verifique a ortografia</p>
                                    </div>
                                ) : (
                                    <div>
                                        <svg className="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p>Nenhum ativo disponível</p>
                                    </div>
                                )}
                            </div>
                        ) : (
                            <div className="divide-y divide-gray-200">
                                {filteredAvailableAssets.map((asset) => (
                                    <div
                                        key={asset.id}
                                        className={`p-4 hover:bg-gray-50 transition-colors ${
                                            selectedAssetIds.includes(asset.id) ? 'bg-blue-50' : ''
                                        }`}
                                    >
                                        <label className="flex items-start space-x-3 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                checked={selectedAssetIds.includes(asset.id)}
                                                onChange={() => handleToggleAsset(asset.id)}
                                                className="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            />
                                            <div className="flex-1 min-w-0">
                                                <div className="flex items-center justify-between">
                                                    <p className="text-sm font-medium text-gray-900 truncate">
                                                        {asset.name}
                                                    </p>
                                                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Disponível
                                                    </span>
                                                </div>
                                                <div className="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                    <span className="font-mono">{asset.qr_code}</span>
                                                    {asset.serial_number && (
                                                        <span>SN: {asset.serial_number}</span>
                                                    )}
                                                    {asset.sector && (
                                                        <span>Setor: {asset.sector.name}</span>
                                                    )}
                                                </div>
                                                {asset.description && (
                                                    <p className="mt-1 text-sm text-gray-600 truncate">
                                                        {asset.description}
                                                    </p>
                                                )}
                                            </div>
                                        </label>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            )}

            {showSelected && (
                <div className="border border-gray-200 rounded-lg">
                    <div className="max-h-96 overflow-y-auto">
                        {selectedAssets.length === 0 ? (
                            <div className="p-8 text-center text-gray-500">
                                <svg className="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p>Nenhum ativo selecionado</p>
                                <p className="text-sm mt-1">Selecione ativos na aba "Ativos Disponíveis"</p>
                            </div>
                        ) : (
                            <div className="divide-y divide-gray-200">
                                {selectedAssets.map((asset) => (
                                    <div key={asset.id} className="p-4 bg-green-50">
                                        <div className="flex items-start justify-between">
                                            <div className="flex-1 min-w-0">
                                                <div className="flex items-center justify-between">
                                                    <p className="text-sm font-medium text-gray-900 truncate">
                                                        {asset.name}
                                                    </p>
                                                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Selecionado
                                                    </span>
                                                </div>
                                                <div className="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                    <span className="font-mono">{asset.qr_code}</span>
                                                    {asset.serial_number && (
                                                        <span>SN: {asset.serial_number}</span>
                                                    )}
                                                    {asset.sector && (
                                                        <span>Setor: {asset.sector.name}</span>
                                                    )}
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                onClick={() => handleRemoveSelected(asset.id)}
                                                className="ml-2 p-1 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-full transition-colors"
                                                title="Remover da seleção"
                                            >
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
};

export default AssetSelectorAdvanced;