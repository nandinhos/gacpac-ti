import SGAITILayout from '@/Layouts/SGAITILayout';
import { Head, useForm } from '@inertiajs/react';

export default function Edit({ user, sectors }) {
    const { data, setData, put, processing, errors } = useForm({
        name: user.name || '',
        rank: user.rank || '',
        military_id: user.military_id || '',
        sector_id: user.sector_id || '',
        email: user.email || '',
        phone: user.phone || '',
        user_role: (user.user_role || 'user').toLowerCase(),
        is_active: user.is_active || false,
    });

    const userRoles = [
        { value: 'user', label: 'Usuário', description: 'Usuário padrão com permissões básicas' },
        { value: 'commission', label: 'Comissão', description: 'Membro da comissão de inventário' },
        { value: 'admin', label: 'Administrador', description: 'Acesso completo ao sistema' },
    ];

    const ranks = [
        'Soldado',
        'Cabo',
        '3º Sargento',
        '2º Sargento',
        '1º Sargento',
        'Subtenente',
        'Asp Of',
        '2º Tenente',
        '1º Tenente',
        'Capitão',
        'Major',
        'Ten Cel',
        'Cel',
        'Gen Bda',
        'Gen Div',
        'Gen Ex',
        'Coronel Aviador',
        'Major Especialista',
        'Capitão de Infantaria',
    ];

    const submit = (e) => {
        e.preventDefault();
        put(route('users.update', user.id));
    };

    return (
        <SGAITILayout
            header={
                <div className="flex justify-between items-center">
                    <div>
                        <h2 className="text-xl font-semibold leading-tight text-gray-800">
                            Editar Usuário
                        </h2>
                        <p className="mt-1 text-sm text-gray-600">
                            {user.name}
                        </p>
                    </div>
                    <div className="flex space-x-3">
                        <a
                            href={route('users.show', user.id)}
                            className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Visualizar
                        </a>
                        <a
                            href={route('users.index')}
                            className="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Voltar
                        </a>
                    </div>
                </div>
            }
        >
            <Head title={`Editar ${user.name} - SGAITI`} />

            <div className="py-6">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <form onSubmit={submit} className="space-y-6">
                        {/* Informações Pessoais */}
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Informações Pessoais
                                </h3>
                                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div className="sm:col-span-2">
                                        <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                                            Nome Completo *
                                        </label>
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Ex: João da Silva Santos"
                                            required
                                        />
                                        {errors.name && <p className="mt-1 text-sm text-red-600">{errors.name}</p>}
                                    </div>

                                    <div>
                                        <label htmlFor="rank" className="block text-sm font-medium text-gray-700">
                                            Posto/Graduação *
                                        </label>
                                        <select
                                            name="rank"
                                            id="rank"
                                            value={data.rank}
                                            onChange={(e) => setData('rank', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            required
                                        >
                                            <option value="">Selecione o posto</option>
                                            {ranks.map((rank) => (
                                                <option key={rank} value={rank}>
                                                    {rank}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.rank && <p className="mt-1 text-sm text-red-600">{errors.rank}</p>}
                                    </div>

                                    <div>
                                        <label htmlFor="military_id" className="block text-sm font-medium text-gray-700">
                                            Identificação Militar *
                                        </label>
                                        <input
                                            type="text"
                                            name="military_id"
                                            id="military_id"
                                            value={data.military_id}
                                            onChange={(e) => setData('military_id', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Ex: 123.456.789-01"
                                            required
                                        />
                                        {errors.military_id && <p className="mt-1 text-sm text-red-600">{errors.military_id}</p>}
                                    </div>

                                    <div>
                                        <label htmlFor="sector_id" className="block text-sm font-medium text-gray-700">
                                            Setor *
                                        </label>
                                        <select
                                            name="sector_id"
                                            id="sector_id"
                                            value={data.sector_id}
                                            onChange={(e) => setData('sector_id', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            required
                                        >
                                            <option value="">Selecione um setor</option>
                                            {sectors.map((sector) => (
                                                <option key={sector.id} value={sector.id}>
                                                    {sector.name}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.sector_id && <p className="mt-1 text-sm text-red-600">{errors.sector_id}</p>}
                                    </div>

                                    <div>
                                        <label htmlFor="phone" className="block text-sm font-medium text-gray-700">
                                            Telefone
                                        </label>
                                        <input
                                            type="tel"
                                            name="phone"
                                            id="phone"
                                            value={data.phone}
                                            onChange={(e) => setData('phone', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Ex: (11) 99999-9999"
                                        />
                                        {errors.phone && <p className="mt-1 text-sm text-red-600">{errors.phone}</p>}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Credenciais */}
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Credenciais de Acesso
                                </h3>
                                <div className="space-y-6">
                                    <div>
                                        <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                            E-mail
                                        </label>
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="usuario@exemplo.com"
                                        />
                                        {errors.email && <p className="mt-1 text-sm text-red-600">{errors.email}</p>}
                                    </div>

                                    <div className="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                        <div className="flex">
                                            <div className="flex-shrink-0">
                                                <svg className="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fillRule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                                </svg>
                                            </div>
                                            <div className="ml-3">
                                                <h3 className="text-sm font-medium text-yellow-800">
                                                    Alteração de Senha
                                                </h3>
                                                <div className="mt-2 text-sm text-yellow-700">
                                                    <p>
                                                        Deixe os campos de senha em branco se não quiser alterar a senha atual.
                                                        Se preencher, a senha será atualizada.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Papel e Status */}
                        <div className="bg-white shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Papel e Status
                                </h3>
                                <div className="space-y-6">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-3">
                                            Papel no Sistema *
                                        </label>
                                        <div className="space-y-3">
                                            {userRoles.map((role) => (
                                                <div key={role.value} className="flex items-center">
                                                    <input
                                                        id={role.value}
                                                        name="user_role"
                                                        type="radio"
                                                        value={role.value}
                                                        checked={data.user_role === role.value}
                                                        onChange={(e) => setData('user_role', e.target.value)}
                                                        className="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                                                    />
                                                    <label htmlFor={role.value} className="ml-3 block">
                                                        <span className="text-sm font-medium text-gray-900">
                                                            {role.label}
                                                        </span>
                                                        <span className="block text-sm text-gray-500">
                                                            {role.description}
                                                        </span>
                                                    </label>
                                                </div>
                                            ))}
                                        </div>
                                        {errors.user_role && <p className="mt-1 text-sm text-red-600">{errors.user_role}</p>}
                                    </div>

                                    <div className="flex items-start">
                                        <div className="flex items-center h-5">
                                            <input
                                                id="is_active"
                                                name="is_active"
                                                type="checkbox"
                                                checked={data.is_active}
                                                onChange={(e) => setData('is_active', e.target.checked)}
                                                className="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                            />
                                        </div>
                                        <div className="ml-3 text-sm">
                                            <label htmlFor="is_active" className="font-medium text-gray-700">
                                                Usuário Ativo
                                            </label>
                                            <p className="text-gray-500">
                                                Marque esta opção para permitir o acesso ao sistema
                                            </p>
                                        </div>
                                    </div>
                                    {errors.is_active && <p className="mt-1 text-sm text-red-600">{errors.is_active}</p>}
                                </div>
                            </div>
                        </div>

                        {/* Actions */}
                        <div className="flex justify-end space-x-3">
                            <a
                                href={route('users.show', user.id)}
                                className="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium"
                            >
                                Cancelar
                            </a>
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50"
                            >
                                {processing ? 'Salvando...' : 'Salvar Alterações'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </SGAITILayout>
    );
}
