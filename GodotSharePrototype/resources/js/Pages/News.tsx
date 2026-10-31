import { Link, Head } from '@inertiajs/react';
import { PageProps } from '@/types';

export default function News({ auth }: PageProps<{}>) {
    return (
        <>
            <article className="prose prose-sm sm:prose-base lg:prose-base max-w-5xl mx-2 lg:mx-auto bg-white border border-gray-200 rounded-xl shadow-md px-4 py-8 sm:px-8">
        
                <h1 className="text-center">お知らせ</h1>

                <h2>2022/01/02</h2>
                <ul>
                    <li>不具合2を修正しました</li>
                    <li>機能2を追加しました</li>
                </ul>

                <h2>2022/01/01</h2>
                <ul>
                    <li>不具合1を修正しました</li>
                    <li>機能1を追加しました</li>
                </ul>

            </article>
        </>
    );
}
