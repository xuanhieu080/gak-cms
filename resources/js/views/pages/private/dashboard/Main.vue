<template>
    <Page>
        <div class="dasboard-admin space-y-6">
            <h3 class="text-3xl mt-0">Thống kê chung</h3>
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Tiền bán
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0">
                                Hôm nay:
                            </span>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(100000000) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Hôm qua:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Tháng này:</span
                            >
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Tháng trước:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                    </div>
                </div>
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Tiền trả
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0">
                                Hôm nay:
                            </span>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Hôm qua:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Tháng này:
                            </span>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Tháng trước:
                            </span>
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                    </div>
                </div>
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Tiền hủy
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Hôm nay:</span
                            >
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(50000000) }} vnđ</span
                            >
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0">
                                Hôm qua:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ</span
                            >
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Tháng này:</span
                            >
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ</span
                            >
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Tháng trước:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ</span
                            >
                        </div>
                    </div>
                </div>
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Lượng truy cập
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Hôm nay:</span
                            >
                            <span class="number break-all">{{
                                formatCurrencyNumber(100)
                            }}</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Hôm qua:</span
                            >
                            <span class="number break-all !text-red-400">{{
                                formatCurrencyNumber(0)
                            }}</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Tháng này:</span
                            >
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(100) }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="flex-grow-0 flex-shrink-0"
                                >Tháng trước:</span
                            >
                            <span class="number break-all !text-red-400">{{
                                formatCurrencyNumber(0)
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Hoạt động
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Tổng tiền bán:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(100000000) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Tổng tiền trả:
                            </div>
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Tổng tiền hủy:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Hóa đơn bán:
                            </div>
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Hóa đơn hủy:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Kho hàng
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Số lượng sản phẩm tồn kho:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(100000000) }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Sản phẩm hết hàng:
                            </div>
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Sản phẩm sắp hết hàng:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="statistic-box product-section">
                <div class="title text-2xl font-semibold mb-4">Sản phẩm</div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-4">
                        <div class="flex-grow-0 flex-shrink-0 text-xl">
                            Sản phẩm đang bán:
                        </div>
                        <div class="grid grid-cols-4 gap-4">
                            <a-card hoverable>
                                <template #cover>
                                    <img
                                        alt="example"
                                        src="https://os.alipayobjects.com/rmsportal/QBnOOoLaAfKPirc.png"
                                    />
                                </template>
                                <a-card-meta title="Europe Street beat">
                                    <template #description>www.gak.vn</template>
                                </a-card-meta>
                            </a-card>
                            <a-card hoverable>
                                <template #cover>
                                    <img
                                        alt="example"
                                        src="https://os.alipayobjects.com/rmsportal/QBnOOoLaAfKPirc.png"
                                    />
                                </template>
                                <a-card-meta title="Europe Street beat">
                                    <template #description>www.gak.vn</template>
                                </a-card-meta>
                            </a-card>
                            <a-card hoverable>
                                <template #cover>
                                    <img
                                        alt="example"
                                        src="https://os.alipayobjects.com/rmsportal/QBnOOoLaAfKPirc.png"
                                    />
                                </template>
                                <a-card-meta title="Europe Street beat">
                                    <template #description>www.gak.vn</template>
                                </a-card-meta>
                            </a-card>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 prose">
                        <div class="flex-grow-0 flex-shrink-0 text-2xl">
                            Nhóm sản phẩm đang bán:
                        </div>
                        <ul class="mt-0">
                            <li>Áo 3M</li>
                            <li>Mũ bảo hộ</li>
                            <li>Giày bảo hộ</li>
                            <li>Kính bảo hộ</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="statistic-box bill-section">
                <div class="title text-2xl font-semibold mb-4">Hóa đơn</div>
                <div class="flex flex-col gap-4 w-full">
                    <div v-for="quantity in 6" class="bill-item underline">
                        {{ quantity }}). Hóa đơn {{ "ABC" }} - Người tạo
                        <b>{{ "Nguyễn Văn A" }}</b>
                    </div>
                </div>
            </div>
            <a-card
                :bordered="false"
                class="example-chart"
                title="Line Chart Example"
            >
                <div class="line-chart-wrapper h-[500px]">
                    <Line
                        ref="lineChart"
                        :data="chartData"
                        :options="chartOptions"
                    />
                </div>
            </a-card>
            <div class="grid grid-cols-2 gap-6">
                <a-card
                    :bordered="false"
                    class="example-chart"
                    title="Pie Chart Example"
                >
                    <div class="pie-chart-wrapper h-[500px]">
                        <Pie
                            class="pie-chart-box"
                            ref="pieChart"
                            :data="pieData"
                            :options="pieOptions"
                        />
                    </div>
                </a-card>
                <a-card
                    :bordered="false"
                    :body-style="{ height: auto }"
                    class="example-chart"
                    title="Bar Chart Example"
                >
                    <div class="bar-chart-wrapper h-[500px]">
                        <Bar
                            ref="barChart"
                            :data="barData"
                            :options="barOptions"
                        />
                    </div>
                </a-card>
            </div>
        </div>
    </Page>
</template>

<script setup>
import Page from "@/views/layouts/Page";
import { ref, watch, reactive } from "vue";
import { Bar, Line, Pie } from "vue-chartjs";
import {
    Chart,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
} from "chart.js";

const chartData = ref({
    labels: ["January", "February", "March", "April", "May"],
    datasets: [
        {
            label: "Tổng số đơn hàng",
            data: [100, 175, 50, 126, 34],
            borderColor: "#0C6AB0",
            backgroundColor: "#0C6AB0",
            pointStyle: false,
            // tension: 0.2,
            borderJoinStyle: "round",
        },
        {
            label: "Số lượng sản phẩm bán ra",
            backgroundColor: "#6A0BAD",
            borderColor: "#6A0BAD",
            data: [196, 122, 100, 22, 200],
            pointStyle: false,
            borderJoinStyle: "round",
            // tension: 0.2,
        },
    ],
});
const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        mode: "index",
        intersect: false,
    },
    stacked: false,
    scales: {
        x: {
            grid: {
                display: false,
            },
            grace: "5%",
            ticks: {
                stepSize: 20,
            },
        },
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 30,
            },
        },
    },
    plugins: {
        legend: {
            display: false,
        },

        datalabels: {
            display: false,
        },
    },
});

const pieData = ref({
    labels: [
        "3M sọc",
        "Áo trơn",
        "Quần In nổi",
        "Mũ bảo hiểm",
        "Bảo hộ phản quang",
        "Giày bảo hộ",
    ],
    datasets: [
        {
            label: "Số lượng: ",
            data: [10, 20, 30, 40, 50, 10],
            backgroundColor: [
                "#0C6AB0",
                "#ea4335",
                "#fbbc04",
                "#34a853",
                "#ff6d01",
                "#46bdc6",
            ],
            hoverOffset: 2,
        },
    ],
});
const pieOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    layout: {
        padding: {
            top: 16,
            left: 16,
            right: 16,
            bottom: 16,
        },
    },
    plugins: {
        legend: {
            display: true,
            position: "right",
            onClick: () => {},
        },
        datalabels: {
            color: "white",
        },
        zoomOutPercentage: 55,
        tooltip: {
            enabled: true,
        },
    },
});

const barData = ref({
    labels: ["Desktop", "Mobile", "Tablet"],
    datasets: [
        {
            label: "My First Dataset",
            data: [50, 199, 135],
            backgroundColor: [
                "#1bffd9",
                "rgba(255, 159, 64, 1)",
                "rgba(255, 205, 86, 1)",
            ],
            borderColor: ["#1bffd9", "rgb(255, 159, 64)", "rgb(255, 205, 86)"],
            borderWidth: 1,
        },
    ],
});

const barOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        x: {
            grid: {
                display: false,
            },
        },
        y: {
            ticks: {
                stepSize: 50,
            },
        },
    },
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            enabled: false,
        },
    },
});
const formatCurrencyNumber = (number) => {
    return number.toLocaleString();
};
</script>

<style lang="scss" scoped>
.statistic-box {
    background-color: white;
    padding: 24px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    &.row-flex {
        flex-direction: row;
        justify-content: flex-start;
        color: white;
        gap: 24px;
        background-color: #0c6ab0;
        .number {
            color: white;
        }
    }
    .number {
        color: #004b67;
        font-weight: 600;
        font-size: 26px;
    }
    hr {
        height: 100%;
        width: 2px;
        opacity: 1;
        color: white;
        margin: 0;
        flex-shrink: 0;
        flex-grow: 0;
        border: 1px solid;
    }
}
</style>
