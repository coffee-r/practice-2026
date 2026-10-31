import { Link, Head } from '@inertiajs/react';
import { PageProps } from '@/types';
import Footer from '@/Components/Footer';

export default function Game({ auth }: PageProps<{}>) {
    return (
        <>
            <Head title="Godot Share" />

            {/* ヘッダー */}
            <div className="flex justify-between mt-6 px-8">
                <Link href={route('home')} >Godot Share</Link>
                {auth.user ? (
                    <Link href={route('dashboard')} >{auth.user.name}</Link>
                ) : (
                    <Link href={route('login')} >ログイン</Link>
                )}
            </div>

            {/* ゲーム */}
            <div>
            <iframe  frameBorder="0" scrolling="no" height="700px" src="https://d2i1ms3alk1esl.cloudfront.net/index.html" allow="autoplay; fullscreen *; geolocation; microphone; camera; midi; monetization; xr-spatial-tracking; gamepad; gyroscope; accelerometer; xr; cross-origin-isolated" id="game_drop"></iframe>

            </div>

            {/* フッター */}
            <Footer />
        </>
    );
}
