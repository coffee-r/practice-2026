import { Link, Head } from '@inertiajs/react';
import { PageProps } from '@/types';
import Footer from '@/Components/Footer';

type GameCardType = {
    game_id: string,
    game_name: string,
    user_name: string,
    user_view_name: string
}

export default function Home({ auth, newGames }: PageProps<{ newGames: Array<GameCardType>,  }>) {
    return (
        <>
            {console.log(newGames)}
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

            {/* ゲーム一覧 */}
            <div>
                <div className="flex justify-center flex-wrap">

                    <div className="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-4">
                        <img className="w-full" src="/game-thumbnail.jpg" alt="Sunset in the mountains" />
                        <div className="px-6 py-4">
                            <div className="font-bold text-xl mb-2">ゲーム名</div>
                            <p className="text-gray-700 text-base">
                                こーひーあーる
                            </p>
                        </div>
                    </div>

                    <div className="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-4">
                        <img className="w-full" src="/game-thumbnail.jpg" alt="Sunset in the mountains" />
                        <div className="px-6 py-4">
                            <div className="font-bold text-xl mb-2">ゲーム名</div>
                            <p className="text-gray-700 text-base">
                                こーひーあーる
                            </p>
                        </div>
                    </div>

                    <div className="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-4">
                        <img className="w-full" src="/game-thumbnail.jpg" alt="Sunset in the mountains" />
                        <div className="px-6 py-4">
                            <div className="font-bold text-xl mb-2">ゲーム名</div>
                            <p className="text-gray-700 text-base">
                                こーひーあーる
                            </p>
                        </div>
                    </div>

                    <div className="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-4">
                        <img className="w-full" src="/game-thumbnail.jpg" alt="Sunset in the mountains" />
                        <div className="px-6 py-4">
                            <div className="font-bold text-xl mb-2">ゲーム名</div>
                            <p className="text-gray-700 text-base">
                                こーひーあーる
                            </p>
                        </div>
                    </div>

                    <div className="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-4">
                        <img className="w-full" src="/game-thumbnail.jpg" alt="Sunset in the mountains" />
                        <div className="px-6 py-4">
                            <div className="font-bold text-xl mb-2">ゲーム名</div>
                            <p className="text-gray-700 text-base">
                                こーひーあーる
                            </p>
                        </div>
                    </div>

                    <div className="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-4">
                        <img className="w-full" src="/game-thumbnail.jpg" alt="Sunset in the mountains" />
                        <div className="px-6 py-4">
                            <div className="font-bold text-xl mb-2">ゲーム名</div>
                            <p className="text-gray-700 text-base">
                                こーひーあーる
                            </p>
                        </div>
                    </div>
                    
                    <div className="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-4">
                        <img className="w-full" src="/game-thumbnail.jpg" alt="Sunset in the mountains" />
                        <div className="px-6 py-4">
                            <div className="font-bold text-xl mb-2">ゲーム名</div>
                            <p className="text-gray-700 text-base">
                                こーひーあーる
                            </p>
                        </div>
                    </div>

                    <div className="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-4">
                        <img className="w-full" src="/game-thumbnail.jpg" alt="Sunset in the mountains" />
                        <div className="px-6 py-4">
                            <div className="font-bold text-xl mb-2">ゲーム名</div>
                            <p className="text-gray-700 text-base">
                                こーひーあーる
                            </p>
                        </div>
                    </div>

                    <div className="max-w-sm rounded overflow-hidden shadow-lg mx-4 my-4">
                        <img className="w-full" src="/game-thumbnail.jpg" alt="Sunset in the mountains" />
                        <div className="px-6 py-4">
                            <div className="font-bold text-xl mb-2">ゲーム名</div>
                            <p className="text-gray-700 text-base">
                                こーひーあーる
                            </p>
                        </div>
                    </div>

                </div>

            </div>

            {/* フッター */}
            <Footer />
        </>
    );
}
