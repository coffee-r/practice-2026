import { Head, Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Button } from '@/Components/ui/button';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuItem } from "@/Components/ui/dropdown-menu";
import { Table, TableHeader, TableRow, TableHead, TableBody, TableCell } from "@/Components/ui/table";
import { Badge } from "@/Components/ui/badge";
import { JSXElementConstructor, ReactElement, SVGProps } from 'react';
import { Seller } from '@/types/seller';
import BasicLayout from '@/Layouts/BasicLayout';

const SellerIndex = ({ auth, sellers }: PageProps<{sellers: Array<Seller>}>) => {

    return (
        <>
            <Head title="出品者一覧" />
            <h1 className="font-semibold text-lg md:text-2xl">出品者一覧</h1>
            <div className="border shadow-sm rounded-lg">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>名前</TableHead>
                            <TableHead>メールアドレス</TableHead>
                            <TableHead className="hidden md:table-cell">承認ステータス</TableHead>
                            <TableHead className="text-right">アクション</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {sellers.map((seller, index) => {
                            return (
                                <TableRow key={index}>
                                    <TableCell className="font-medium">{seller.name}</TableCell>
                                    <TableCell>
                                        {seller.email}
                                    </TableCell>
                                    <TableCell className="hidden md:table-cell">
                                        {seller.approval_status == 'pending' && (
                                            <Badge variant="outline" className="rounded-full px-2 py-1">
                                                {seller.approval_status}
                                            </Badge>
                                        )}
                                        {seller.approval_status == 'approved' && (
                                            <Badge variant="outline" className="rounded-full bg-green-500/10 px-2 py-1 text-green-500 dark:bg-green-500/20">
                                                {seller.approval_status}
                                            </Badge>
                                        )}
                                        {seller.approval_status == 'rejected' && (
                                            <Badge variant="outline" className="rounded-full bg-red-500/10 px-2 py-1 text-red-500 dark:bg-red-500/20">
                                                {seller.approval_status}
                                            </Badge>
                                        )}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button variant="ghost" size="icon">
                                            <MoveHorizontalIcon className="w-4 h-4" />
                                            <span className="sr-only">Actions</span>
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem><Link href={route('seller.edit', seller.id)}>編集する</Link></DropdownMenuItem>
                                        </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                            )
                        })}
                    </TableBody>
                </Table>
            </div>
        </>
    );
}

const MoveHorizontalIcon = (props: JSX.IntrinsicAttributes & SVGProps<SVGSVGElement>) => {
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
        <polyline points="18 8 22 12 18 16" />
        <polyline points="6 8 2 12 6 16" />
        <line x1="2" x2="22" y1="12" y2="12" />
        </svg>
    )
}
  
SellerIndex.layout = (page: ReactElement<any, string | JSXElementConstructor<any>>) => <BasicLayout user={page.props.auth.user}>{page}</BasicLayout>;
export default SellerIndex