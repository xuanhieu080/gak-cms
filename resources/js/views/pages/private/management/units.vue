<template>
    <Page>
        <div class="main-pyc">
            <a-breadcrumb :routes="routes" class="bg-gray-100 p-2">
                <template #itemRender="{ route, params, routes, paths }">
                    <span
                        v-if="routes.indexOf(route) === routes.length - 1"
                        class="text-blue-500"
                        >{{ route.breadcrumbName }}</span
                    >
                    <router-link
                        v-else
                        :to="{ name: route.name }"
                        class="!text-black"
                        >{{ route.breadcrumbName }}</router-link
                    >
                </template>
            </a-breadcrumb>
            <a-collapse
                v-model:activeKey="openSearchTicket"
                :bordered="false"
                ghost
            >
                <a-collapse-panel
                    key="1"
                    class="bg-blue-500 !text-white"
                    :show-arrow="false"
                >
                    <template #header>
                        <div class="text-white">Tìm kiếm</div>
                    </template>
                    <div class="grid grid-cols-6 gap-6 w-full">
                        <div class="search-item">
                            <div class="title">Keyword</div>
                            <a-input
                                v-model:value="keyword_search"
                                type="text"
                            />
                        </div>
                        <div class="search-item">
                            <div class="title">Mã kho</div>
                            <a-input v-model:value="ticket_code" />
                        </div>
                        <div class="search-item">
                            <div class="title">Tên sản phẩm</div>
                            <a-input v-model:value="customer_name" />
                        </div>
                        <div class="search-item">
                            <div class="title">Địa chỉ</div>
                            <a-input v-model:value="customer_address" />
                        </div>
                        <div class="search-item">
                            <div class="title">Trạng thái</div>
                            <a-select
                                v-model:value="selected_status"
                                show-search
                                placeholder="Chọn"
                                :options="options"
                                :filter-option="filterOption"
                                @change="handleChange"
                                class="w-full"
                            ></a-select>
                        </div>
                        <div class="search-item col-span-2">
                            <div class="title">Ngày yêu cầu</div>
                            <a-range-picker
                                v-model:value="selected_date_request"
                            />
                        </div>
                    </div>
                    <div class="filter-buttons mt-6 flex items-center gap-4">
                        <a-button type="primary">Tìm kiếm</a-button>
                        <a-button @click="resetSearchBox">Làm mới</a-button>
                    </div>
                </a-collapse-panel>
            </a-collapse>
            <div class="pyc-table border border-blue-500 flex flex-col">
                <div class="title-box bg-blue-500 text-white p-4">
                    Quản lý kho hàng
                </div>
                <div
                    class="flex items-center gap-4 justify-between w-full p-4 flex-wrap"
                >
                    <div
                        class="filter-box flex items-center lg:justify-between gap-4 flex-wrap"
                    >
                        <a-select
                            v-model:value="selected_ticket_product"
                            show-search
                            allowClear="true"
                            placeholder="Chọn sản phẩm"
                            :options="options"
                            :filter-option="filterOption"
                            @change="handleChange"
                            class="w-[150px]"
                        ></a-select>
                        <a-select
                            v-model:value="selected_ticket_solver"
                            show-search
                            placeholder="Solver by product type"
                            :options="options"
                            :filter-option="filterOption"
                            @change="handleChange"
                            class="w-[200px]"
                        ></a-select>
                        <a-select
                            v-model:value="selected_ticket_engineer"
                            show-search
                            placeholder="Tất cả kỹ thuật viên"
                            :options="options"
                            :filter-option="filterOption"
                            @change="handleChange"
                            class="w-[200px]"
                        ></a-select>
                        <a-button type="primary">Gán</a-button>
                    </div>
                    <div
                        v-if="false"
                        class="statistic-ticket flex items-center gap-4 flex-wrap"
                    >
                        <div class="statistic-ticket-item">
                            <div class="title">Chờ xử lý:</div>
                            <div class="counting-box pending">57/57</div>
                        </div>
                        <div class="statistic-ticket-item">
                            <div class="title">Đang xử lý:</div>
                            <div class="counting-box progress">88/88</div>
                        </div>
                        <div class="statistic-ticket-item">
                            <div class="title">Đã xử lý:</div>
                            <div class="counting-box finish">5/559303</div>
                        </div>
                        <div class="statistic-ticket-item">
                            <div class="title">Đang theo dõi:</div>
                            <div class="counting-box following">234/234</div>
                        </div>
                    </div>
                </div>
                <div class="table-container px-3">
                    <a-table
                        :row-selection="rowSelection"
                        :columns="columns"
                        :pagination="pagination"
                        :loading="loading"
                        :row-key="(record) => record.ticket_code"
                        :data-source="exampleData"
                        @change="handleTableChange"
                        bordered
                        :scroll="{ x: 'max-content' }"
                    >
                        <template #headerCell="{ column }">
                            <template v-if="column.dataIndex != 'phone'">
                                <div class="text-blue-500">
                                    {{ column.title }}
                                </div>
                            </template>
                        </template>
                        <template #bodyCell="{ column, text }">
                            <template v-if="column.dataIndex === 'ticket_code'">
                                <div class="text-blue-500">{{ text }}</div>
                            </template>
                            <template
                                v-if="column.dataIndex === 'customer_request'"
                            >
                                <div
                                    :class="
                                        !text ? 'text-red-500 italic fs-12' : ''
                                    "
                                >
                                    {{ text ? text : "(Không có)" }}
                                </div>
                            </template>
                            <template
                                v-if="
                                    column.dataIndex === 'customer_name' ||
                                    column.dataIndex === 'address' ||
                                    column.dataIndex === 'phone' ||
                                    column.dataIndex === 'ticket_gcic_1' ||
                                    column.dataIndex === 'ticket_gcic_2'
                                "
                            >
                                <div class="underline flex items-center gap-2">
                                    {{ text }}
                                    <PhoneTwoTone
                                        class="filter-white"
                                        v-if="column.dataIndex === 'phone'"
                                    />
                                </div>
                            </template>
                            <template v-if="column.dataIndex === 'status'">
                                <div
                                    v-if="text == 'deposit'"
                                    class="status-box pending"
                                >
                                    {{ text }}
                                </div>
                                <div
                                    v-if="text == 'withdrawal'"
                                    class="status-box progress"
                                >
                                    {{ text }}
                                </div>
                                <div
                                    v-if="text == 'invoice'"
                                    class="status-box finish"
                                >
                                    {{ text }}
                                </div>
                            </template>
                            <template
                                v-if="
                                    column.dataIndex === 'result' ||
                                    column.dataIndex === 'calling_time_1' ||
                                    column.dataIndex === 'calling_time_2' ||
                                    column.dataIndex === 'calling_time_3'
                                "
                            >
                                <div
                                    :class="
                                        !text ? 'text-red-500 italic fs-12' : ''
                                    "
                                >
                                    {{ text ? text : "(Không có)" }}
                                </div>
                            </template>
                            <template
                                v-if="column.dataIndex === 'result_ticket'"
                            >
                                <div
                                    v-if="text == 'deposit'"
                                    class="status-box pending"
                                >
                                    {{ text }}
                                </div>
                                <div
                                    v-if="text == 'withdrawal'"
                                    class="status-box progress"
                                >
                                    {{ text }}
                                </div>
                                <div
                                    v-if="text == 'invoice'"
                                    class="status-box finish"
                                >
                                    {{ text }}
                                </div>
                            </template>
                        </template>
                        <template #title="{}">
                            <div
                                class="flex items-center gap-4 justify-between w-full"
                            >
                                <div>
                                    Trình bày
                                    <b>{{
                                        (current > 1
                                            ? pageSize * (current - 1) + 1
                                            : 1) +
                                        "-" +
                                        pageSize * current
                                    }}</b>
                                    trong số <b>{{ "100" }}</b> mục.
                                </div>
                                <div class="flex items-center flex-wrap gap-2">
                                    <a-button
                                        type="primary"
                                        class="flex items-center"
                                        @click="handleToggleSearchBox"
                                    >
                                        <template #icon>
                                            <SearchOutlined />
                                        </template>
                                        Tìm kiếm
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        class="flex items-center"
                                        @click="handleCreatePYC"
                                    >
                                        <template #icon>
                                            <PlusOutlined />
                                        </template>
                                        Tạo kho
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        danger
                                        class="flex items-center"
                                    >
                                        <template #icon>
                                            <DeleteOutlined />
                                        </template>
                                        Xóa
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        class="flex items-center"
                                    >
                                        <template #icon>
                                            <SyncOutlined />
                                        </template>
                                        Tạo lại
                                    </a-button>
                                </div>
                            </div>
                        </template>
                    </a-table>
                </div>
            </div>
        </div>
    </Page>
</template>

<script setup>
import {
    SearchOutlined,
    PlusOutlined,
    DeleteOutlined,
    SyncOutlined,
    ReloadOutlined,
    UserOutlined,
    PhoneTwoTone,
} from "@ant-design/icons-vue";
import { ref, computed } from "vue";
import { usePagination } from "vue-request";
import { useRouter } from "vue-router";
import axios from "axios";
import Page from "@/views/layouts/Page";

const openSearchTicket = ref([]);
const keyword_search = ref(null);
const ticket_code = ref(null);
const selected_shift_information = ref(null);
const customer_name = ref(null);
const customer_address = ref(null);
const type_of_device = ref(null);
const selected_engineer = ref(null);
const selected_produce = ref(null);
const selected_status = ref(null);
const selected_result = ref(null);
const selected_result_ticket = ref(null);
const selected_agency_code = ref(null);
const selected_date_request = ref(null);
const selected_ticket_product = ref(null);
const selected_ticket_solver = ref(null);
const selected_ticket_engineer = ref(null);

const routes = ref([
    {
        name: "home",
        breadcrumbName: "Trang chủ",
    },
    {
        name: "warehouse",
        breadcrumbName: "Quản lý Đơn Vị Tính",
    },
]);

const columns = [
    {
        title: "Mã kho",
        dataIndex: "ticket_code",
        sorter: true,
        width: "70px",
    },
    {
        title: "Tên kho",
        dataIndex: "customer_request",
        sorter: true,
        width: "150px",
    },
    {
        title: "Địa chỉ",
        dataIndex: "address",
        sorter: true,
        width: "150px",
    },
    {
        title: "Số điện thoại",
        dataIndex: "phone",
        sorter: true,
        width: "150px",
    },
    {
        title: "Quản lý bởi",
        dataIndex: "created_by",
        sorter: true,
        width: "150px",
    },
    {
        title: "Sản phẩm",
        dataIndex: "product",
        sorter: true,
        width: "150px",
    },
    {
        title: "Ngày yêu cầu",
        dataIndex: "created_at",
        sorter: true,
        width: "150px",
    },
    {
        title: "Cập nhật lúc",
        dataIndex: "updated_at",
        sorter: true,
        width: "150px",
    },
    {
        title: "Trạng thái",
        dataIndex: "status",
        sorter: true,
        width: "150px",
    },
    {
        title: "Kết quả",
        dataIndex: "result",
        sorter: true,
        width: "150px",
    },
];

const exampleData = ref([
    {
        customer_request: false,
        customer_name: "Ms. Joanna Hermiston II",
        address: "417 Haag Cliffs",
        phone: "844-331-4997",
        created_by: "Sally Grimes",
        product: "Concrete",
        ticket_code: 96172,
        created_at: "2061-05-21T22:17:41.217Z",
        updated_at: "2035-01-07T05:04:50.609Z",
        status: "deposit",
        result: "Reactive",
        ticket_gcic_1: 39344,
        ticket_gcic_2: 34814,
        result_ticket: false,
        calling_time_1: "2053-12-29T10:47:19.005Z",
        calling_time_2: "2095-03-06T07:32:13.834Z",
        calling_time_3: "2049-12-12T23:29:49.347Z",
        id: "1",
    },

    {
        customer_request: false,
        customer_name: "Ed Langworth",
        address: "41573 Will Street",
        phone: "226-391-7007",
        created_by: "Guadalupe Ullrich",
        product: "Reggae",
        ticket_code: 22278,
        created_at: "2013-12-14T14:19:19.792Z",
        updated_at: "2018-09-20T06:29:49.850Z",
        status: "withdrawal",
        result: "East",
        ticket_gcic_1: 81869,
        ticket_gcic_2: 6361,
        result_ticket: true,
        calling_time_1: "1996-06-17T17:08:27.315Z",
        calling_time_2: "1996-09-23T20:56:35.229Z",
        calling_time_3: "2058-09-22T03:30:06.279Z",
        id: "2",
    },

    {
        customer_request: false,
        customer_name: "Veronica VonRueden",
        address: "028 Jack Mills",
        phone: "456-995-1315",
        created_by: "Hazel Senger DVM",
        product: "Rue",
        ticket_code: 22718,
        created_at: "2062-09-07T17:24:17.586Z",
        updated_at: "2060-01-28T14:02:08.600Z",
        status: "invoice",
        result: "initiative",
        ticket_gcic_1: 21126,
        ticket_gcic_2: 98303,
        result_ticket: false,
        calling_time_1: "2083-03-07T05:20:09.740Z",
        calling_time_2: "2034-01-18T09:53:32.671Z",
        calling_time_3: "2025-12-12T01:25:44.724Z",
        id: "3",
    },

    {
        customer_request: false,
        customer_name: "Lois Hegmann",
        address: "042 Anthony Shoals",
        phone: "840-529-0068",
        created_by: "Jim Legros V",
        product: "Legacy",
        ticket_code: 63887,
        created_at: "2055-02-04T07:58:49.567Z",
        updated_at: "2098-09-04T17:09:59.262Z",
        status: "deposit",
        result: "meter",
        ticket_gcic_1: 2280,
        ticket_gcic_2: 4738,
        result_ticket: true,
        calling_time_1: "2011-02-02T18:48:09.907Z",
        calling_time_2: "2037-06-21T22:23:40.170Z",
        calling_time_3: "2006-10-22T03:35:01.675Z",
        id: "4",
    },

    {
        customer_request: false,
        customer_name: "Jason Lubowitz",
        address: "95775 Dena Curve",
        phone: "536-228-7546",
        created_by: "Dr. Pedro Nikolaus",
        product: "applications",
        ticket_code: 16375,
        created_at: "2066-05-20T04:04:36.866Z",
        updated_at: "2043-09-11T03:32:53.694Z",
        status: "withdrawal",
        result: "F2M",
        ticket_gcic_1: 57100,
        ticket_gcic_2: 24318,
        result_ticket: false,
        calling_time_1: "2053-11-02T10:40:24.593Z",
        calling_time_2: "2016-08-13T08:11:43.439Z",
        calling_time_3: "2053-09-15T21:19:28.285Z",
        id: "5",
    },

    {
        customer_request: false,
        customer_name: "Toni Braun",
        address: "758 O'Keefe Path",
        phone: "371-614-6097",
        created_by: "Shelley Kohler",
        product: "invoice",
        ticket_code: 53061,
        created_at: "2053-07-06T15:43:58.096Z",
        updated_at: "2054-07-09T11:11:23.455Z",
        status: "invoice",
        result: "female",
        ticket_gcic_1: 16355,
        ticket_gcic_2: 99024,
        result_ticket: true,
        calling_time_1: "2012-01-10T14:40:22.207Z",
        calling_time_2: "2001-08-24T23:45:49.648Z",
        calling_time_3: "2064-12-08T16:52:44.567Z",
        id: "6",
    },

    {
        customer_request: false,
        customer_name: "Mable Breitenberg",
        address: "216 Linwood Burg",
        phone: "999-844-5506",
        created_by: "Johnny Ondricka",
        product: "programming",
        ticket_code: 19991,
        created_at: "2040-04-22T14:40:03.508Z",
        updated_at: "2082-06-06T22:31:43.228Z",
        status: "invoice",
        result: "Wooden",
        ticket_gcic_1: 46827,
        ticket_gcic_2: 35178,
        result_ticket: false,
        calling_time_1: "2008-12-29T13:58:35.366Z",
        calling_time_2: "1992-07-13T07:59:02.986Z",
        calling_time_3: "2062-12-04T20:39:41.880Z",
        id: "7",
    },

    {
        customer_request: false,
        customer_name: "Kurt Hilll",
        address: "544 Parker Via",
        phone: "942-304-2039",
        created_by: "Heidi Swaniawski",
        product: "maroon",
        ticket_code: 28805,
        created_at: "2061-04-18T01:53:38.592Z",
        updated_at: "2075-12-23T01:55:36.916Z",
        status: "withdrawal",
        result: "Northwest",
        ticket_gcic_1: 4801,
        ticket_gcic_2: 92900,
        result_ticket: false,
        calling_time_1: "2079-11-03T11:07:23.877Z",
        calling_time_2: "2088-05-01T04:28:57.216Z",
        calling_time_3: "2083-10-09T08:48:07.671Z",
        id: "8",
    },

    {
        customer_request: false,
        customer_name: "Patricia Corwin",
        address: "204 Kyle Ville",
        phone: "975-200-0940",
        created_by: "Mrs. Brooke Ziemann",
        product: "than",
        ticket_code: 9764,
        created_at: "1990-08-02T09:13:26.785Z",
        updated_at: "2079-12-24T08:27:37.227Z",
        status: "invoice",
        result: "Aluminium",
        ticket_gcic_1: 61947,
        ticket_gcic_2: 99241,
        result_ticket: true,
        calling_time_1: "2020-10-12T17:09:29.903Z",
        calling_time_2: "1992-06-23T23:42:47.838Z",
        calling_time_3: "2049-11-15T21:45:19.887Z",
        id: "9",
    },

    {
        customer_request: false,
        customer_name: "Howard Nikolaus",
        address: "3150 Angela Landing",
        phone: "501-996-9801",
        created_by: "Patsy Hand",
        product: "Books",
        ticket_code: 5676,
        created_at: "2033-06-12T23:35:16.609Z",
        updated_at: "2088-12-06T15:09:34.929Z",
        status: "invoice",
        result: "Jazz",
        ticket_gcic_1: 90841,
        ticket_gcic_2: 95586,
        result_ticket: false,
        calling_time_1: "2005-01-26T01:10:30.106Z",
        calling_time_2: "2094-12-12T20:07:51.256Z",
        calling_time_3: "2056-04-18T12:15:30.600Z",
        id: "10",
    },

    {
        customer_request: false,
        customer_name: "Ronald Ziemann",
        address: "43011 Caesar Loaf",
        phone: "928-315-8273",
        created_by: "Alison Marquardt",
        product: "Directives",
        ticket_code: 25758,
        created_at: "2028-11-23T11:25:55.125Z",
        updated_at: "2001-08-28T21:12:30.790Z",
        status: "deposit",
        result: "Electric",
        ticket_gcic_1: 90531,
        ticket_gcic_2: 31648,
        result_ticket: false,
        calling_time_1: "2016-12-02T10:09:07.044Z",
        calling_time_2: "1992-05-28T22:05:56.523Z",
        calling_time_3: "2088-07-11T14:22:45.897Z",
        id: "11",
    },

    {
        customer_request: false,
        customer_name: "Clarence Koch",
        address: "1210 Schoen Crossroad",
        phone: "510-602-0161",
        created_by: "Marvin Conroy",
        product: "Northeast",
        ticket_code: 92644,
        created_at: "2027-05-14T22:25:44.299Z",
        updated_at: "2070-12-26T12:24:33.698Z",
        status: "deposit",
        result: "midst",
        ticket_gcic_1: 2257,
        ticket_gcic_2: 55191,
        result_ticket: false,
        calling_time_1: "2075-03-30T21:40:39.407Z",
        calling_time_2: "2020-02-29T17:09:06.248Z",
        calling_time_3: "2045-05-01T11:38:39.350Z",
        id: "12",
    },

    {
        customer_request: false,
        customer_name: "Kerry Nicolas",
        address: "75109 Ruth Way",
        phone: "997-469-8309",
        created_by: "Edmund Walsh",
        product: "middleware",
        ticket_code: 58032,
        created_at: "1993-08-01T22:32:17.182Z",
        updated_at: "2094-02-09T14:01:02.785Z",
        status: "payment",
        result: "Nebraska",
        ticket_gcic_1: 77963,
        ticket_gcic_2: 23062,
        result_ticket: true,
        calling_time_1: "2090-06-06T19:41:58.640Z",
        calling_time_2: "2003-03-19T23:44:05.513Z",
        calling_time_3: "2016-12-31T14:24:42.920Z",
        id: "13",
    },

    {
        customer_request: false,
        customer_name: "Gerard Predovic",
        address: "4904 Domenic Well",
        phone: "986-239-9117",
        created_by: "Carla Ruecker",
        product: "Diesel",
        ticket_code: 21171,
        created_at: "2034-12-19T12:15:50.323Z",
        updated_at: "2055-07-01T20:39:24.854Z",
        status: "deposit",
        result: "exuding",
        ticket_gcic_1: 82465,
        ticket_gcic_2: 78237,
        result_ticket: false,
        calling_time_1: "2030-11-12T05:18:16.176Z",
        calling_time_2: "2060-04-20T18:40:41.025Z",
        calling_time_3: "2044-04-16T10:55:56.504Z",
        id: "14",
    },

    {
        customer_request: false,
        customer_name: "Charlie Bernier",
        address: "5594 Ernser Court",
        phone: "907-535-2033",
        created_by: "Sherry Rosenbaum",
        product: "incentivize",
        ticket_code: 68116,
        created_at: "2092-07-04T08:25:50.735Z",
        updated_at: "2099-12-19T01:18:58.519Z",
        status: "payment",
        result: "HDD",
        ticket_gcic_1: 40901,
        ticket_gcic_2: 88216,
        result_ticket: true,
        calling_time_1: "2099-01-02T22:44:59.469Z",
        calling_time_2: "2054-12-07T07:58:12.829Z",
        calling_time_3: "2081-09-29T09:46:59.750Z",
        id: "15",
    },

    {
        customer_request: false,
        customer_name: "Lorena Brakus",
        address: "044 Gislason Lane",
        phone: "833-855-9136",
        created_by: "Rose Hand",
        product: "Tricycle",
        ticket_code: 4408,
        created_at: "2050-08-17T19:27:31.091Z",
        updated_at: "1997-03-19T23:30:48.872Z",
        status: "deposit",
        result: "Gloves",
        ticket_gcic_1: 85660,
        ticket_gcic_2: 21989,
        result_ticket: false,
        calling_time_1: "2053-08-08T05:59:06.833Z",
        calling_time_2: "2038-01-17T14:18:22.780Z",
        calling_time_3: "2056-04-28T04:31:00.272Z",
        id: "16",
    },

    {
        customer_request: false,
        customer_name: "Julian Schroeder Sr.",
        address: "334 Kaycee Views",
        phone: "266-364-1043",
        created_by: "Kim Stark",
        product: "navigate",
        ticket_code: 24570,
        created_at: "2070-05-12T19:41:09.043Z",
        updated_at: "2061-02-21T12:05:01.514Z",
        status: "invoice",
        result: "interfaces",
        ticket_gcic_1: 56366,
        ticket_gcic_2: 39881,
        result_ticket: false,
        calling_time_1: "2053-12-16T22:28:22.894Z",
        calling_time_2: "1994-01-03T00:01:24.950Z",
        calling_time_3: "2066-01-27T12:58:09.272Z",
        id: "17",
    },

    {
        customer_request: false,
        customer_name: "Joann Baumbach Sr.",
        address: "2672 Kaden Hills",
        phone: "950-606-0374",
        created_by: "Ruben Kuvalis",
        product: "South",
        ticket_code: 68681,
        created_at: "2027-12-07T08:29:34.659Z",
        updated_at: "1990-01-03T15:06:21.073Z",
        status: "payment",
        result: "Electronics",
        ticket_gcic_1: 12810,
        ticket_gcic_2: 82991,
        result_ticket: true,
        calling_time_1: "2087-08-14T07:21:37.671Z",
        calling_time_2: "2040-09-08T01:33:06.994Z",
        calling_time_3: "2095-07-26T20:50:27.883Z",
        id: "18",
    },

    {
        customer_request: false,
        customer_name: "Marion Quitzon",
        address: "8245 Jevon Walks",
        phone: "849-446-1032",
        created_by: "Sarah Torp",
        product: "Toys",
        ticket_code: 82924,
        created_at: "2018-01-14T03:47:05.755Z",
        updated_at: "2068-04-13T15:59:15.930Z",
        status: "payment",
        result: "molestias",
        ticket_gcic_1: 17218,
        ticket_gcic_2: 68196,
        result_ticket: true,
        calling_time_1: "2011-09-28T02:48:50.285Z",
        calling_time_2: "2099-12-29T06:01:56.505Z",
        calling_time_3: "1998-02-10T12:20:58.750Z",
        id: "19",
    },

    {
        customer_request: false,
        customer_name: "Angelo Johns",
        address: "9947 Bogisich Pines",
        phone: "733-385-4356",
        created_by: "Willis Sawayn",
        product: "Baby",
        ticket_code: 24398,
        created_at: "1991-06-16T15:03:53.477Z",
        updated_at: "2066-09-10T12:03:32.142Z",
        status: "payment",
        result: "Peso",
        ticket_gcic_1: 76010,
        ticket_gcic_2: 82245,
        result_ticket: false,
        calling_time_1: "2049-06-18T08:21:16.005Z",
        calling_time_2: "2010-11-06T02:18:24.683Z",
        calling_time_3: "2073-08-19T17:08:00.289Z",
        id: "20",
    },
]);

const options = ref([
    {
        value: "jack",
        label: "Jack",
    },
    {
        value: "lucy",
        label: "Lucy",
    },
    {
        value: "tom",
        label: "Tom",
    },
]);
const router = useRouter();
const handleCreatePYC = () => {
    router.push({ name: "pyc-create" });
};

const handleToggleSearchBox = () => {
    if (openSearchTicket.value.length > 0) {
        openSearchTicket.value = [];
    } else {
        openSearchTicket.value[0] = 1;
    }
};

const filterOption = (input, option) => {
    return option.value.toLowerCase().indexOf(input.toLowerCase()) >= 0;
};

const handleChange = (value) => {
    console.log(`selected ${value}`);
};

const queryData = (params) => {
    return axios.get('/api/materials', {
        params,
    });
};

const {
    data: dataSource,
    run,
    loading,
    current,
    totalPage,
    pageSize,
} = usePagination(queryData, {
    defaultParams: [
        {
            limit: 10,
        },
    ],
    pagination: {
        currentKey: "page",
        pageSizeKey: "limit",
        totalKey: "dataSource.total",
    },
});

const pagination = computed(() => ({
    total: totalPage,
    current: current.value,
    pageSize: pageSize.value,
}));

const handleReloadData = () => {
    run({
        limit: 10,
        page: 1,
    });
};

const handleTableChange = (pag, filters, sorter) => {
    run({
        limit: pag.pageSize,
        page: pag?.current,
        sort_by: sorter.field,
        order_by: sorter.order,
        ...filters,
    });
};

const rowSelection = {
    onChange: (selectedRowKeys, selectedRows) => {},
    getCheckboxProps: (record) => ({
        // Column configuration not to be checked
        ticket_code: record.ticket_code,
    }),
};
</script>

<style lang="scss" scoped>
.main-pyc {
    @apply space-y-4;
    .search-item {
        @apply flex flex-col gap-1;
        .title {
            @apply font-medium;
        }
    }
    .pyc-table {
        .statistic-ticket {
            .statistic-ticket-item {
                @apply flex items-center gap-1;
                font-size: 14px;
                .counting-box {
                    padding: 4px 6px;
                    border-radius: 4px;
                    font-weight: bold;
                    text-align: center;
                    color: white;
                    font-size: 13px;
                    &.pending {
                        @apply bg-blue-500;
                    }
                    &.progress {
                        background-color: #f0ad4e;
                    }
                    &.finish {
                        background-color: #5cb85c;
                    }
                    &.following {
                        background-color: #777777;
                    }
                }
            }
        }
    }
}
</style>
<style lang="scss">
.main-pyc {
    .ant-collapse-content-box {
        background-color: white !important;
        border: 1px solid rgb(59 130 246 / var(--tw-bg-opacity));
        border-radius: 8;
    }
    .ant-table-fixed {
        table-layout: fixed;
    }
    .ant-table-cell {
        .status-box {
            width: fit-content;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            &.pending {
                @apply bg-blue-500;
            }
            &.progress {
                background-color: #f0ad4e;
            }
            &.finish {
                background-color: #5cb85c;
            }
            &.following {
                background-color: #777777;
            }
        }
    }
}
.ant-input {
    box-sizing: border-box;
    margin: 0;
    padding: 4px 11px;
    color: rgba(0, 0, 0, 0.88);
    font-size: 14px;
    line-height: 1.5;
    list-style: none;
    position: relative;
    display: inline-block;
    width: 100%;
    min-width: 0;
    background-color: #ffffff;
    background-image: none;
    border-width: 1px;
    border-style: solid;
    border-color: #d9d9d9;
    border-radius: 6px;
    transition: all 0.2s;
}

.ant-select-selection-search {
    input[type="search"] {
        box-shadow: none !important;
    }
}
</style>
