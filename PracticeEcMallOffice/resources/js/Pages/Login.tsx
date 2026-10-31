import { Head, useForm } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Card, CardContent, CardFooter } from "@/Components/ui/card"
import { Label } from "@/Components/ui/label"
import { Input } from "@/Components/ui/input"
import { Button } from "@/Components/ui/button"
import { FormEventHandler, SVGProps } from 'react';

export default function Login({ auth }: PageProps<{}>) {    

    const { data, setData, post, processing, errors } = useForm({
        id: '',
        password: '',
        authError: ''
    })

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <>
            <Head title="ログイン" />
            <div className="flex min-h-screen items-center justify-center bg-gray-100 dark:bg-gray-950">
                <div className="w-full max-w-md space-y-8">
                    <div className="flex flex-col items-center gap-2">
                        <MountainIcon className="h-10 w-10 text-primary" />
                        <h1 className="text-3xl font-bold tracking-tight">EC Mall Office</h1>
                    </div>
                    <form onSubmit={submit}>
                        <Card>
                            <CardContent className="space-y-4 pt-4">
                                <div className="space-y-2">
                                    <Label htmlFor="id">オペレーターコード</Label>
                                    <Input value={data.id} onChange={e => setData('id', e.target.value)} id="id" placeholder="1" required />
                                </div>
                                <div className="space-y-2">
                                    <div className="flex items-center justify-between">
                                        <Label htmlFor="password">パスワード</Label>
                                    </div>
                                    <Input value={data.password} onChange={e => setData('password', e.target.value)} id="password" type="password" required />
                                </div>
                                {errors.authError && <p className="text-sm text-red-600 mt-2">{errors.authError}</p>}
                            </CardContent>
                            <CardFooter>
                                <Button className="w-full">ログイン</Button>
                            </CardFooter>
                        </Card>
                    </form>
                </div>
            </div>
        </>
    );
}

const MountainIcon = (props: JSX.IntrinsicAttributes & SVGProps<SVGSVGElement>) => {
    return (
        <svg
          {...props}
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          strokeWidth="2"
          strokeLinecap="round"
          strokeLinejoin="round"
        >
          <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
        </svg>
    )
}