import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ user }) {
    const getRoleLabel = (role) => {
        switch (role) {
            case 'ADMIN':
                return 'Administrador';
            case 'COMMISSION':
                return 'Comissão';
            case 'USER':
            default:
                return 'Usuário';
        }
    };

    const getRoleColor = (role) => {
        switch (role) {
            case 'ADMIN':
                return 'bg-red-100 text-red-800';
            case 'COMMISSION':
                return 'bg-blue-100 text-blue-800';
            case 'USER':
            default:
                return 'bg-green-100 text-green-800';
        }
    };

    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <div>
                        <h2 className="text-xl font-semibold leading-tight text-gray-800">
                            Detalhes do Usuário
                        </h2>
                        <p className="mt-1 text-sm text-gray-600">
                            {user.name}
                        </p>
                    </div>
                    <div className="flex space-x-3">
                        <Link
                            href={route('users.edit', user.id)}
                            className="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Editar
                        </Link>
                        <Link
                            href={route('users.index')}
                            className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Voltar
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={`${user.name} - SGAITI`} />

            <div className="py-6">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Informações Principais */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Cabeçalho do Usuário */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <div className="flex items-start justify-between">
                                        <div className="flex items-center space-x-4">
                                            <div className="flex-shrink-0">
                                                <div className="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span className="text-2xl font-bold text-gray-700">
                                                        {user.name.charAt(0).toUpperCase()}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <h1 className="text-2xl font-bold text-gray-900">
                                                    {user.name}
                                                </h1>
                                                <div className="flex items-center space-x-2 mt-1">
                                                    <span className="text-lg text-gray-600">
                                                        {user.rank}
                                                    </span>
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getRoleColor(user.user_role)}`}>
                                                        {getRoleLabel(user.user_role)}
                                                    </span>
                                                </div>
                                                <div className="flex items-center space-x-2 mt-2">
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                        user.is_active
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-red-100 text-red-800'
                                                    }`}>
                                                        {user.is_active ? 'Ativo' : 'Inativo'}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="flex space-x-2">
                                            <Link
                                                href={route('users.edit', user.id)}
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

                            {/* Informações Detalhadas */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        Informações Pessoais
                                    </h3>
                                    <dl className="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Nome Completo</dt>
                                            <dd className="mt-1 text-sm text-gray-900">{user.name}</dd>
                                        </div>
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Posto/Graduação</dt>
                                            <dd className="mt-1 text-sm text-gray-900">{user.rank}</dd>
                                        </div>
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Identificação Militar</dt>
                                            <dd className="mt-1 text-sm text-gray-900">{user.military_id}</dd>
                                        </div>
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">E-mail</dt>
                                            <dd className="mt-1 text-sm text-gray-900">{user.email || 'Não informado'}</dd>
                                        </div>
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Telefone</dt>
                                            <dd className="mt-1 text-sm text-gray-900">{user.phone || 'Não informado'}</dd>
                                        </div>
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Setor</dt>
                                            <dd className="mt-1 text-sm text-gray-900">
                                                {user.sector ? user.sector.name : 'Não definido'}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Papel no Sistema</dt>
                                            <dd className="mt-1">
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getRoleColor(user.user_role)}`}>
                                                    {getRoleLabel(user.user_role)}
                                                </span>
                                            </dd>
                                        </div>
                                        <div>
                                            <dt className="text-sm font-medium text-gray-500">Status</dt>
                                            <dd className="mt-1">
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                    user.is_active
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                }`}>
                                                    {user.is_active ? 'Ativo' : 'Inativo'}
                                                </span>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            {/* Atividades Recentes */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        Atividades Recentes
                                    </h3>
                                    <div className="space-y-3">
                                        <div className="flex items-start space-x-3">
                                            <div className="flex-shrink-0">
                                                <div className="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg className="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="text-sm font-medium text-gray-900">
                                                    Primeiro acesso ao sistema
                                                </p>
                                                <p className="text-sm text-gray-500">
                                                    {new Date(user.created_at).toLocaleDateString('pt-BR')}
                                                </p>
                                            </div>
                                        </div>

                                        {user.updated_at !== user.created_at && (
                                            <div className="flex items-start space-x-3">
                                                <div className="flex-shrink-0">
                                                    <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                        <svg className="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <p className="text-sm font-medium text-gray-900">
                                                        Perfil atualizado
                                                    </p>
                                                    <p className="text-sm text-gray-500">
                                                        {new Date(user.updated_at).toLocaleDateString('pt-BR')}
                                                    </p>
                                                </div>
                                            </div>
                                        )}
                                    </div>
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
                                        <div className="text-center">
                                            <div className="text-3xl font-bold text-gray-900">
                                                {user.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div className="text-sm text-gray-500 mt-1">
                                                Inicial do nome
                                            </div>
                                        </div>
                                        <div className="border-t border-gray-200 pt-4">
                                            <div className="flex items-center justify-between">
                                                <span className="text-sm text-gray-600">Status</span>
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                    user.is_active
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                }`}>
                                                    {user.is_active ? 'Ativo' : 'Inativo'}
                                                </span>
                                            </div>
                                            <div className="flex items-center justify-between mt-2">
                                                <span className="text-sm text-gray-600">Papel</span>
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getRoleColor(user.user_role)}`}>
                                                    {getRoleLabel(user.user_role)}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Ações Rápidas */}
                            <div className="bg-white shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                        Ações
                                    </h3>
                                    <div className="space-y-3">
                                        <Link
                                            href={route('users.edit', user.id)}
                                            className="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                        >
                                            <svg className="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editar Usuário
                                        </Link>
                                        <button className="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <svg className="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Enviar E-mail
                                        </button>
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
