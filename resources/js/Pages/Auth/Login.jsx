import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        military_id: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Login - SGTI-GAC" />

            <div className="text-center mb-8">
                <h1 className="text-3xl font-bold text-gray-900">SGTI-GAC</h1>
                <p className="text-gray-600 mt-2">Sistema de Gestão de TI do GAC-PAC</p>
            </div>

            {status && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    {status}
                </div>
            )}

            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="military_id" value="Identificação Militar" />

                    <TextInput
                        id="military_id"
                        type="text"
                        name="military_id"
                        value={data.military_id}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={true}
                        placeholder="Digite sua identificação militar"
                        onChange={(e) => setData('military_id', e.target.value)}
                    />

                    <InputError message={errors.military_id} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Senha" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="current-password"
                        placeholder="Digite sua senha"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4 block">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) =>
                                setData('remember', e.target.checked)
                            }
                        />
                        <span className="ms-2 text-sm text-gray-600">
                            Lembrar de mim
                        </span>
                    </label>
                </div>

                <div className="mt-6">
                    <PrimaryButton 
                        className="w-full flex justify-center" 
                        disabled={processing}
                    >
                        {processing ? 'Entrando...' : 'Entrar'}
                    </PrimaryButton>
                </div>

                {/* Credenciais de teste */}
                <div className="mt-6 p-4 bg-gray-50 rounded-lg">
                    <p className="text-xs text-gray-500 text-center mb-2">Credenciais de teste:</p>
                    <div className="grid grid-cols-1 gap-1 text-xs text-gray-600">
                        <div><strong>Admin:</strong> admin / admin123</div>
                        <div><strong>Comissão:</strong> comissao001 / comissao123</div>
                        <div><strong>Usuário:</strong> user001 / user123</div>
                    </div>
                </div>
            </form>
        </GuestLayout>
    );
}
