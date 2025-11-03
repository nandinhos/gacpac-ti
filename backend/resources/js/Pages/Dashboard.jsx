import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ stats = {} }) {
    const defaultStats = {
        totalAssets: stats.totalAssets || 0,
        activeCustody: stats.activeCustody || 0,
        pendingInventory: stats.pendingInventory || 0,
        totalUsers: stats.totalUsers || 0,
    };

    const statCards = [
        {
            name: 'Total de Ativos',
            value: defaultStats.totalAssets,
            icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            color: 'text-blue-600',
            bgColor: 'bg-blue-50',
            description: 'Ativos registrados no sistema',
        },
        {
            name: 'Cautelas Ativas',
            value: defaultStats.activeCustody,
            icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
            color: 'text-green-600',
            bgColor: 'bg-green-50',
            description: 'Equipamentos emprestados',
        },
        {
            name: 'Inventários Pendentes',
            value: defaultStats.pendingInventory,
            icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            color: 'text-yellow-600',
            bgColor: 'bg-yellow-50',
            description: 'Sessões de inventário ativas',
        },
        {
            name: 'Total de Usuários',
            value: defaultStats.totalUsers,
            icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
            color: 'text-purple-600',
            bgColor: 'bg-purple-50',
            description: 'Usuários cadastrados',
        },
    ];

    const recentActivities = [
        {
            id: 1,
            type: 'asset_created',
            title: 'Novo ativo cadastrado',
            description: 'Notebook Dell Latitude foi adicionado ao inventário',
            time: '2 horas atrás',
            icon: 'M12 6v6m0 0v6m0-6h6m-6 0H6',
            color: 'text-green-600',
            bgColor: 'bg-green-100',
        },
        {
            id: 2,
            type: 'custody_created',
            title: 'Cautela realizada',
            description: 'Sgt. Silva retirou um projetor para uso em reunião',
            time: '4 horas atrás',
            icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
            color: 'text-blue-600',
            bgColor: 'bg-blue-100',
        },
        {
            id: 3,
            type: 'inventory_started',
            title: 'Inventário iniciado',
            description: 'Sessão de inventário do setor TI foi aberta',
            time: '1 dia atrás',
            icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            color: 'text-yellow-600',
            bgColor: 'bg-yellow-100',
        },
    ];

    return (
        <SGAITILayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard - SGAITI
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Welcome Message */}
                    <div className="mb-8">
                        <h1 className="text-2xl font-bold text-gray-900">
                            Bem-vindo ao Sistema de Gestão de TI do GAC-PAC
                        </h1>
                        <p className="mt-2 text-gray-600">
                            Gerencie ativos, cautelas e inventários de forma eficiente e organizada.
                        </p>
                    </div>

                    {/* Statistics Cards */}
                    <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                        {statCards.map((stat) => (
                            <div key={stat.name} className="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow">
                                <div className="p-5">
                                    <div className="flex items-center">
                                        <div className="flex-shrink-0">
                                            <div className={`w-8 h-8 rounded-md ${stat.bgColor} flex items-center justify-center`}>
                                                <svg className={`w-5 h-5 ${stat.color}`} fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={stat.icon} />
                                                </svg>
                                            </div>
                                        </div>
                                        <div className="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt className="text-sm font-medium text-gray-500 truncate">
                                                    {stat.name}
                                                </dt>
                                                <dd className="text-lg font-medium text-gray-900">
                                                    {stat.value}
                                                </dd>
                                                <dt className="text-xs text-gray-400 mt-1">
                                                    {stat.description}
                                                </dt>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {/* Recent Activity */}
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Atividades Recentes
                                </h3>
                                <div className="space-y-4">
                                    {recentActivities.map((activity) => (
                                        <div key={activity.id} className="flex items-start space-x-3">
                                            <div className="flex-shrink-0">
                                                <div className={`w-8 h-8 rounded-full ${activity.bgColor} flex items-center justify-center`}>
                                                    <svg className={`w-4 h-4 ${activity.color}`} fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={activity.icon} />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="text-sm font-medium text-gray-900">
                                                    {activity.title}
                                                </p>
                                                <p className="text-sm text-gray-500">
                                                    {activity.description}
                                                </p>
                                                <p className="text-xs text-gray-400 mt-1">
                                                    {activity.time}
                                                </p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                                <div className="mt-6">
                                    <a
                                        href="#"
                                        className="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                    >
                                        Ver todas as atividades
                                    </a>
                                </div>
                            </div>
                        </div>

                        {/* Quick Actions */}
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Ações Rápidas
                                </h3>
                                <div className="grid grid-cols-2 gap-4">
                                    <a
                                        href="#"
                                        className="relative block w-full bg-white rounded-lg p-4 border-2 border-dashed border-gray-300 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <div className="flex items-center justify-center">
                                            <svg className="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            <span className="ml-2 text-sm font-medium text-gray-900">Novo Ativo</span>
                                        </div>
                                    </a>
                                    <a
                                        href="#"
                                        className="relative block w-full bg-white rounded-lg p-4 border-2 border-dashed border-gray-300 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <div className="flex items-center justify-center">
                                            <svg className="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <span className="ml-2 text-sm font-medium text-gray-900">Nova Cautela</span>
                                        </div>
                                    </a>
                                    <a
                                        href="#"
                                        className="relative block w-full bg-white rounded-lg p-4 border-2 border-dashed border-gray-300 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <div className="flex items-center justify-center">
                                            <svg className="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <span className="ml-2 text-sm font-medium text-gray-900">Inventário</span>
                                        </div>
                                    </a>
                                    <a
                                        href="#"
                                        className="relative block w-full bg-white rounded-lg p-4 border-2 border-dashed border-gray-300 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <div className="flex items-center justify-center">
                                            <svg className="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span className="ml-2 text-sm font-medium text-gray-900">Relatórios</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* System Status */}
                    <div className="mt-8">
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Status do Sistema
                                </h3>
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div className="flex items-center">
                                        <div className="flex-shrink-0">
                                            <div className="w-3 h-3 bg-green-400 rounded-full"></div>
                                        </div>
                                        <div className="ml-3">
                                            <p className="text-sm font-medium text-gray-900">Banco de Dados</p>
                                            <p className="text-sm text-gray-500">Conectado</p>
                                        </div>
                                    </div>
                                    <div className="flex items-center">
                                        <div className="flex-shrink-0">
                                            <div className="w-3 h-3 bg-green-400 rounded-full"></div>
                                        </div>
                                        <div className="ml-3">
                                            <p className="text-sm font-medium text-gray-900">Sistema de Arquivos</p>
                                            <p className="text-sm text-gray-500">Operacional</p>
                                        </div>
                                    </div>
                                    <div className="flex items-center">
                                        <div className="flex-shrink-0">
                                            <div className="w-3 h-3 bg-green-400 rounded-full"></div>
                                        </div>
                                        <div className="ml-3">
                                            <p className="text-sm font-medium text-gray-900">API</p>
                                            <p className="text-sm text-gray-500">Online</p>
                                        </div>
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
