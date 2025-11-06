import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import AssetSelectorAdvanced from '@/Components/AssetSelectorAdvanced';

// Componente simplificado para adicionar ativos por QR Code (backup/complemento)
const QuickQrSelector = ({ availableAssets, selectedAssetIds, onSelectionChange }) => {
    const [assetQrCode, setAssetQrCode] = useState('');
    const [error, setError] = useState('');

    const handleAddAsset = () => {
        const qrCode = assetQrCode.trim().toLowerCase();
        if (!qrCode) return;

        setError('');
        const asset = availableAssets.find(a => a.qr_code.toLowerCase() === qrCode);
        
        if (asset) {
            if (!selectedAssetIds.includes(asset.id)) {
                onSelectionChange([...selectedAssetIds, asset.id]);
                setAssetQrCode('');
                setError('');
            } else {
                setError('Este ativo já foi selecionado.');
            }
        } else {
            setError('Ativo não encontrado ou não disponível.');
        }
    };

    return (
        <div className="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h3 className="text-sm font-medium text-blue-900 mb-3 flex items-center">
                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h4.01M12 12v4.01" />
                </svg>
                Adicionar Rapidamente por QR Code
            </h3>
            <div className="flex items-center space-x-2">
                <input 
                    type="text" 
                    value={assetQrCode} 
                    onChange={(e) => setAssetQrCode(e.target.value)} 
                    onKeyPress={(e) => e.key === 'Enter' && handleAddAsset()} 
                    placeholder="Digite ou escaneie o QR Code"
                    className="flex-1 block w-full border-blue-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                />
                <button 
                    type="button" 
                    onClick={handleAddAsset} 
                    className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors"
                >
                    Adicionar
                </button>
            </div>
            {error && (
                <p className="text-red-600 text-sm mt-2 flex items-center">
                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                    </svg>
                    {error}
                </p>
            )}
        </div>
    );
};

export default function Create({ users, assets, nextCautelaNumber }) {
    const { data, setData, post, processing, errors } = useForm({
        cautelaNumber: nextCautelaNumber,
        userId: '',
        checkoutDate: new Date().toISOString().split('T')[0],
        assetIds: [],
        notes: ''
    });

    const availableAssets = useMemo(() => 
        assets.filter(a => a.status === 'Disponível'), 
        [assets]
    );

    const handleSelectionChange = (newAssetIds) => {
        setData('assetIds', newAssetIds);
    };

    function submit(e) {
        e.preventDefault();
        post(route('custody.store'));
    }

    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 flex items-center">
                        <svg className="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Criar Nova Cautela
                    </h2>
                    <Link 
                        href={route('custody.index')} 
                        className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors flex items-center"
                    >
                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Voltar
                    </Link>
                </div>
            }
        >
            <Head title="Nova Cautela - SGAITI" />

            <div className="py-6">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white shadow-lg rounded-lg overflow-hidden">
                        {/* Header com informações */}
                        <div className="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                            <h3 className="text-xl font-semibold text-white flex items-center">
                                <svg className="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Formulário de Criação de Cautela
                            </h3>
                            <p className="text-blue-100 mt-2">
                                Preencha as informações abaixo para criar uma nova cautela e selecione os ativos desejados.
                            </p>
                        </div>
                        
                        <div className="p-8">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="cautelaNumber" className="block text-sm font-medium text-gray-700">Nº da Cautela</label>
                                    <input 
                                        type="text" 
                                        id="cautelaNumber" 
                                        value={data.cautelaNumber} 
                                        onChange={e => setData('cautelaNumber', e.target.value)}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100"
                                        required
                                        readOnly
                                    />
                                    {errors.cautelaNumber && <p className="text-red-500 text-xs mt-1">{errors.cautelaNumber}</p>}
                                </div>
                                <div>
                                    <label htmlFor="checkoutDate" className="block text-sm font-medium text-gray-700">Data de Abertura</label>
                                    <input 
                                        type="date" 
                                        id="checkoutDate" 
                                        value={data.checkoutDate} 
                                        onChange={e => setData('checkoutDate', e.target.value)}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        required
                                    />
                                    {errors.checkoutDate && <p className="text-red-500 text-xs mt-1">{errors.checkoutDate}</p>}
                                </div>
                            </div>

                            <div className="mt-4">
                                <label htmlFor="userId" className="block text-sm font-medium text-gray-700">Militar Responsável</label>
                                <select 
                                    id="userId" 
                                    value={data.userId} 
                                    onChange={e => setData('userId', e.target.value)} 
                                    required 
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                >
                                    <option value="">Selecione um militar</option>
                                    {users.filter(u => u.is_active).map(u => <option key={u.id} value={u.id}>{u.rank} {u.name}</option>)}
                                </select>
                                {errors.userId && <p className="text-red-500 text-xs mt-1">{errors.userId}</p>}
                            </div>

                            <div className="mt-6 space-y-6">
                                <div>
                                    <h3 className="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                        <svg className="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        Seleção de Ativos
                                    </h3>
                                    
                                    {/* Adição rápida por QR Code */}
                                    <QuickQrSelector 
                                        availableAssets={availableAssets}
                                        selectedAssetIds={data.assetIds}
                                        onSelectionChange={handleSelectionChange}
                                    />
                                </div>

                                {/* Seletor avançado principal */}
                                <div>
                                    <AssetSelectorAdvanced 
                                        availableAssets={availableAssets}
                                        selectedAssetIds={data.assetIds}
                                        onSelectionChange={handleSelectionChange}
                                    />
                                </div>
                                
                                {errors.assetIds && (
                                    <div className="bg-red-50 border border-red-200 rounded-md p-3">
                                        <p className="text-red-700 text-sm flex items-center">
                                            <svg className="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                            </svg>
                                            {errors.assetIds}
                                        </p>
                                    </div>
                                )}
                            </div>

                            <div className="mt-4">
                                <label htmlFor="notes" className="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea 
                                    id="notes" 
                                    value={data.notes} 
                                    onChange={e => setData('notes', e.target.value)} 
                                    rows={3}
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                ></textarea>
                                {errors.notes && <p className="text-red-500 text-xs mt-1">{errors.notes}</p>}
                            </div>

                            <div className="mt-8 pt-6 border-t border-gray-200 flex justify-between items-center">
                                <div className="text-sm text-gray-500">
                                    {data.assetIds.length > 0 && (
                                        <span className="flex items-center">
                                            <svg className="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {data.assetIds.length} ativo{data.assetIds.length !== 1 ? 's' : ''} selecionado{data.assetIds.length !== 1 ? 's' : ''}
                                        </span>
                                    )}
                                </div>
                                
                                <div className="flex space-x-4">
                                    <Link 
                                        href={route('custody.index')} 
                                        className="px-6 py-3 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors flex items-center"
                                    >
                                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Cancelar
                                    </Link>
                                    <button 
                                        type="submit" 
                                        disabled={processing || data.assetIds.length === 0} 
                                        className="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                                    >
                                        {processing ? (
                                            <>
                                                <svg className="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Salvando...
                                            </>
                                        ) : (
                                            <>
                                                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                </svg>
                                                Criar Cautela
                                            </>
                                        )}
                                    </button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </SGAITILayout>
    );
}
