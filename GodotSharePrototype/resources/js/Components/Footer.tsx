import { Link } from '@inertiajs/react';

export default function Footer() {
    return (
        <div className="flex justify-center mt-16 px-6 sm:items-center sm:justify-between my-4">

            <div className="flex">
                <div className="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left">
                    <Link href={route('news')} >お知らせ</Link>
                </div>
                <div className="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left ml-4">
                    <Link href={route('terms')} >利用規約</Link>
                </div>
                <div className="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left ml-4">
                    <Link href={route('privacy')} >プライバシーポリシー</Link>
                </div>
            </div>

            <div className="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                ©︎2023 こーひーあーる
            </div>
        </div>
    );
}
