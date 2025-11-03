import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ asset }) {
    const getConditionColor = (condition) => {
        switch (condition) {
            case 'NOVO':
                return 'text-green-600 bg-green-100';
            case 'BOM':
                return 'text-blue-600 bg-blue-100';
            case 'REGULAR':
                return 'text-yellow-600 bg-yellow-100';
            case 'RUIM':
                return 'text-orange-600 bg-orange-100';
            case 'DEFEITUOSO':
                return 'text-red-600 bg-red-100';
            default:
                return 'text-gray-600 bg-gray-100';
        }
    };

    const getConditionLabel = (condition) => {
        switch (condition) {
            case 'NOVO':
                return 'Novo';
            case 'BOM':
                return 'Bom';
            case 'REGULAR':
                return 'Regular';
            case 'RUIM':
                return 'Ruim';
            case 'DEFEITUOSO':
                return 'Defeituoso';
            default:
                return condition;
        }
    };

    const getTypeLabel = (type) => {
        switch (type) {
            case 'COMPUTADOR':
                return 'Computador';
            case 'NOTEBOOK':
                return 'Notebook';
            case 'MONITOR':
                return 'Monitor';
            case 'IMPRESSORA':
                return 'Impressora';
            case 'TELEFONE':
                return 'Telefone';
            case 'OUTROS':
                return 'Outros';
            default:
                return type;
        }
    };

    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <div>
                        <h2 className="text-xl font-semibold leading-tight text-gray-800">
                            Detalhes do Ativo
                        </h2>
                        <p className="mt-1 text-sm text-gray-600">
                            {asset.name}
                        </p>
                    </div>
                    <div className="flex space-x-3">
                        <Link
                            href={route('assets.edit', asset.id)}
                            className="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Editar
                        </Link>
                        <Link
                            href={route('assets.index')}
                            className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Voltar
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={`${asset.name} - SGAITI`} />

            <div className="py-6">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Informações Principais */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Cabeçalho do Ativo */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <div className="flex items-start justify-between">
                                        <div className="flex items-center space-x-4">
                                            <div className="flex-shrink-0">
                                                <div className="w-16 h-16 bg-gray-300 rounded-lg flex items-center justify-center">
                                                    <svg className="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h1 className="text-2xl font-bold text-gray-900">
                                                    {asset.name}
                                                </h1>
                                                <div className="flex items-center space-x-2 mt-1">
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getConditionColor(asset.condition)}`}>
                                                        {getConditionLabel(asset.condition)}
                                                    </span>
                                                    <span className="text-sm text-gray-500">
                                                        {getTypeLabel(asset.type)}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="flex space-x-2">
                                            <Link
                                                href={route('assets.edit', asset.id)}
                                                className="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                            >
                                                <svg className="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Detalhes Técnicos */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        Informações Técnicas
                                    </h3>
                                    <dl className="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Marca</dt>
                                            <dd className="mt-1 text-sm text-gray-900">{asset.brand}</dd>
                                        </div>
                                        {asset.model && (
                                            <div>
                                                <dt className="text-sm font-medium text-gray-500">Modelo</dt>
                                                <dd className="mt-1 text-sm text-gray-900">{asset.model}</dd>
                                            </div>
                                        )}
                                        {asset.serial_number && (
                                            <div>
                                                <dt className="text-sm font-medium text-gray-500">Número de Série</dt>
                                                <dd className="mt-1 text-sm text-gray-900">{asset.serial_number}</dd>
                                            </div>
                                        )}
                                        {asset.patrimony_number && (
                                            <div>
                                                <dt className="text-sm font-medium text-gray-500">Número do Patrimônio</dt>
                                                <dd className="mt-1 text-sm text-gray-900">{asset.patrimony_number}</dd>
                                            </div>
                                        )}
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Tipo</dt>
                                            <dd className="mt-1 text-sm text-gray-900">{getTypeLabel(asset.type)}</dd>
                                        </div>
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Estado</dt>
                                            <dd className="mt-1">
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getConditionColor(asset.condition)}`}>
                                                    {getConditionLabel(asset.condition)}
                                                </span>
                                            </dd>
                                        </div>
                                        {asset.purchase_value && (
                                            <div>
                                                <dt className="text-sm font-medium text-gray-500">Valor de Compra</dt>
                                                <dd className="mt-1 text-sm text-gray-900">
                                                    R$ {parseFloat(asset.purchase_value).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                                                </dd>
                                            </div>
                                        )}
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Setor</dt>
                                            <dd className="mt-1 text-sm text-gray-900">
                                                {asset.sector ? asset.sector.name : 'Não definido'}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            {/* Observações */}
                            {asset.notes && (
                                <div className="bg-white shadow rounded-lg">
                                    <div className="px-4 py-5 sm:p-6">
                                        <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                            Observações
                                        </h3>
                                        <p className="text-sm text-gray-700 whitespace-pre-line">
                                            {asset.notes}
                                        </p>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Sidebar */}
                        <div className="space-y-6">
                            {/* Ações Rápidas */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        Ações
                                    </h3>
                                    <div className="space-y-3">
                                        <Link
                                            href={route('assets.edit', asset.id)}
                                            className="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                        >
                                            <svg className="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editar Ativo
                                        </Link>
                                        <button className="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <svg className="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                            </svg>
                                            Upload Foto
                                        </button>
                                        <button className="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <svg className="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Gerar QR Code
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {/* Timeline */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        Timeline
                                    </h3>
                                    <div className="space-y-3">
                                        <div className="flex items-start space-x-3">
                                            <div className="flex-shrink-0">
                                                <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg className="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="text-sm font-medium text-gray-900">
                                                    Ativo cadastrado
                                                </p>
                                                <p className="text-sm text-gray-500">
                                                    {new Date(asset.created_at).toLocaleDateString('pt-BR')}
                                                </p>
                                            </div>
                                        </div>
                                        {asset.updated_at !== asset.created_at && (
                                            <div className="flex items-start space-x-3">
                                                <div className="flex-shrink-0">
                                                    <div className="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <svg className="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <p className="text-sm font-medium text-gray-900">
                                                        Última atualização
                                                    </p>
                                                    <p className="text-sm text-gray-500">
                                                        {new Date(asset.updated_at).toLocaleDateString('pt-BR')}
                                                    </p>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </SGAITILayout>
    );
}
