import { Head } from '@inertiajs/react';
import { PageProps } from '@/types';
import BasicLayout from '@/Layouts/BasicLayout';
import { ReactElement, JSXElementConstructor, ReactNode, ReactPortal } from 'react';

const SellerIndex = ({ auth }: PageProps<{}>) => {
    return (
        <>
            <Head title="ダッシュボード" />
            <h1 className="font-semibold text-lg md:text-2xl">ダッシュボード</h1>
        </>
    );
}

SellerIndex.layout = (page: ReactElement<any, string | JSXElementConstructor<any>>) => <BasicLayout user={page.props.auth.user}>{page}</BasicLayout>;
export default SellerIndex