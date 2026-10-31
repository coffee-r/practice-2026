import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Post } from '@/types/Post';
import { FormEventHandler } from 'react';
import PostCard from '@/Components/PostCard';

export default function Posts({ auth, posts }: PageProps<{posts: Array<Post>}>) {

    const { data, setData, post, processing, errors } = useForm({
        message: ''
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('posts.store'));
    };

    const { flash } = usePage<any>().props

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Posts</h2>}
        >
            <Head title="Posts" />

            {flash.message && 
                <div className="bg-teal-100 text-teal-900 mx-auto px-20 py-3" role="alert">
                    <div className="flex">
                        <div className=""><svg className="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                        <p className="font-bold">{flash.message}</p>
                    </div>
                </div>
            }
            

            <form className='max-w-7xl mx-auto sm:px-6 lg:px-8 pt-4' onSubmit={submit}>
                <textarea value={data.message} onChange={e => setData('message', e.target.value)} className="py-3 px-4 mb-3 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500" rows={2} placeholder="投稿する"></textarea>
                {errors.message && <p className="text-sm text-red-600 mt-2">{errors.message}</p>}
                <button type="submit" disabled={processing} className="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm">
                    投稿
                </button>
            </form>
            
            {posts.map((post, index) => {
                return <PostCard key={index} post={post} />
            })}

        </AuthenticatedLayout>
    );
}
