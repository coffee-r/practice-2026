import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { auth0 } from "@/lib/auth0";
import Link from "next/link";

export default async function Login() {

    const session = await auth0.getSession()
    console.log(session?.tokenSet.accessToken)

    if (!session) {
        return (
            <div>
                <div className="min-h-screen flex justify-center items-center">
                    <Card className="w-full max-w-sm">
                        <CardHeader>
                            <CardTitle className="text-2xl">ログイン</CardTitle>
                            <CardDescription>利用規約、プライバシーポリシーに同意した上でログインしてください。</CardDescription>
                        </CardHeader>
                        <CardContent>
                        <Button className="w-full" asChild>
                            <Link href="/auth/login">Login</Link>
                        </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        )
    }
    
    return (
        <main>
            <h1>Welcome, {session.user.name}!</h1>
        </main>
    )
}