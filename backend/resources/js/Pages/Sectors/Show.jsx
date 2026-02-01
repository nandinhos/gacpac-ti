import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ sector }) {
    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <div>
                        <h2 className="text-xl font-semibold leading-tight text-gray-800">
                            Detalhes do Setor
                        </h2>
                        <p className="mt-1 text-sm text-gray-600">
                            {sector.name}
                        </p>
                    </div>
                    <div className="flex space-x-3">
                        <Link
                            href={route('sectors.edit', sector.id)}
                            className="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Editar
                        </Link>
                        <Link
                            href={route('sectors.index')}
                            className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Voltar
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={`${sector.name} - SGAITI`} />

            <div className="py-6">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Informações Principais */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Cabeçalho do Setor */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <div className="flex items-start justify-between">
                                        <div className="flex items-center space-x-4">
                                            <div className="flex-shrink-0">
                                                <div className="w-16 h-16 bg-gray-300 rounded-lg flex items-center justify-center">
                                                    <span className="text-2xl font-bold text-gray-700">
                                                        {sector.name.charAt(0).toUpperCase()}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <h1 className="text-2xl font-bold text-gray-900">
                                                    {sector.name}
                                                </h1>
                                                <div className="flex items-center space-x-2 mt-1">
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                        sector.is_active
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-red-100 text-red-800'
                                                    }`}>
                                                        {sector.is_active ? 'Ativo' : 'Inativo'}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="flex space-x-2">
                                            <Link
                                                href={route('sectors.edit', sector.id)}
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

                            {/* Descrição */}
                            {sector.description && (
                                <div className="bg-white shadow rounded-lg">
                                    <div className="px-4 py-5 sm:p-6">
                                        <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                            Descrição
                                        </h3>
                                        <p className="text-sm text-gray-700 whitespace-pre-line">
                                            {sector.description}
                                        </p>
                                    </div>
                                </div>
                            )}

                            {/* Usuários do Setor */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        Usuários do Setor ({sector.users?.length || 0})
                                    </h3>
                                    {sector.users && sector.users.length > 0 ? (
                                        <div className="space-y-3">
                                            {sector.users.map((user) => (
                                                <div key={user.id} className="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                                    <div className="flex-shrink-0">
                                                        <div className="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span className="text-sm font-medium text-gray-700">
                                                                {user.name.charAt(0).toUpperCase()}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <p className="text-sm font-medium text-gray-900">
                                                            {user.name}
                                                        </p>
                                                        <p className="text-sm text-gray-500">
                                                            {user.rank} • ID: {user.military_id}
                                                        </p>
                                                    </div>
                                                    <div className="flex-shrink-0">
                                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                            user.is_active
                                                                ? 'bg-green-100 text-green-800'
                                                                : 'bg-red-100 text-red-800'
                                                        }`}>
                                                            {user.is_active ? 'Ativo' : 'Inativo'}
                                                        </span>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    ) : (
                                        <p className="text-sm text-gray-500">
                                            Nenhum usuário associado a este setor.
                                        </p>
                                    )}
                                </div>
                            </div>

                            {/* Ativos do Setor */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        Ativos do Setor ({sector.assets?.length || 0})
                                    </h3>
                                    {sector.assets && sector.assets.length > 0 ? (
                                        <div className="space-y-3">
                                            {sector.assets.slice(0, 5).map((asset) => (
                                                <div key={asset.id} className="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                                    <div className="flex-shrink-0">
                                                        <div className="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                            <svg className="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <p className="text-sm font-medium text-gray-900">
                                                            {asset.name}
                                                        </p>
                                                        <p className="text-sm text-gray-500">
                                                            {asset.type} • {asset.brand}
                                                        </p>
                                                    </div>
                                                    <div className="flex-shrink-0">
                                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                            asset.condition === 'NOVO' ? 'bg-green-100 text-green-800' :
                                                            asset.condition === 'BOM' ? 'bg-blue-100 text-blue-800' :
                                                            asset.condition === 'REGULAR' ? 'bg-yellow-100 text-yellow-800' :
                                                            'bg-red-100 text-red-800'
                                                        }`}>
                                                            {asset.condition}
                                                        </span>
                                                    </div>
                                                </div>
                                            ))}
                                            {sector.assets.length > 5 && (
                                                <p className="text-sm text-gray-500 text-center">
                                                    E mais {sector.assets.length - 5} ativos...
                                                </p>
                                            )}
                                        </div>
                                    ) : (
                                        <p className="text-sm text-gray-500">
                                            Nenhum ativo associado a este setor.
                                        </p>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Sidebar */}
                        <div className="space-y-6">
                            {/* Estatísticas */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        Estatísticas
                                    </h3>
                                    <div className="space-y-4">
                                        <div className="flex items-center justify-between">
                                            <span className="text-sm text-gray-600">Usuários</span>
                                            <span className="text-sm font-medium text-gray-900">
                                                {sector.users?.length || 0}
                                            </span>
                                        </div>
                                        <div className="flex items-center justify-between">
                                            <span className="text-sm text-gray-600">Ativos</span>
                                            <span className="text-sm font-medium text-gray-900">
                                                {sector.assets?.length || 0}
                                            </span>
                                        </div>
                                        <div className="flex items-center justify-between">
                                            <span className="text-sm text-gray-600">Status</span>
                                            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                sector.is_active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800'
                                            }`}>
                                                {sector.is_active ? 'Ativo' : 'Inativo'}
                                            </span>
                                        </div>
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
                                                    Setor criado
                                                </p>
                                                <p className="text-sm text-gray-500">
                                                    {new Date(sector.created_at).toLocaleDateString('pt-BR')}
                                                </p>
                                            </div>
                                        </div>
                                        {sector.updated_at !== sector.created_at && (
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
                                                        {new Date(sector.updated_at).toLocaleDateString('pt-BR')}
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
