<template>
    <div class="home">
        <h2 class="primary-text">上月结余：<span style="color: #F56C6C;">{{lastMonthSurplus}}</span></h2>
        <h2 class="primary-text">本月累积：<span style="color: #67C23A;">{{thisMonthSurplus}}</span></h2>
        <div>
            <el-date-picker

                    v-model="date"
                    align="right"
                    type="date"
                    placeholder="选择加班日期"
                    :picker-options="pickerOptions">
            </el-date-picker>
            <el-input style="width: 18%;margin: 0 10px;" v-model="hours" placeholder="请输入小时数" type="number" max="12" min="1">
                <template slot="append">小时</template>
            </el-input>
            <el-button type="primary" @click="overtimes(hours)">提交</el-button>
        </div>
        <div style="margin: 30px 0;">
            <el-button @click="overtimes(2)">提交2小时</el-button>
            <el-button @click="overtimes(3)">提交3小时</el-button>
            <el-button @click="overtimes(4)">提交4小时</el-button>
            <el-button @click="overtimes(8)">提交8小时</el-button>
        </div>
    </div>
</template>

<script>
    import {Button, Row, Col, Input, DatePicker, MessageBox} from 'element-ui'
    import {register, setOvertime, surplus} from "../api";

    export default {
        name: 'home',
        data: function () {
            return {
                hours: 1,
                date: new Date(),
                lastMonthSurplus: 0,
                thisMonthSurplus: 0,
                pickerOptions: {
                    disabledDate(time) {
                        let date = new Date();
                        date.setDate(1);
                        return time.getTime() > Date.now() || time.getTime() < date.getTime() - 3600 * 1000 * 24;
                    },
                    shortcuts: [
                        {
                            text: '昨天',
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24);
                                picker.$emit('pick', date);
                            }
                        }, {
                            text: '前天',
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24 * 2);
                                picker.$emit('pick', date);
                            }
                        }]
                }
            }
        },
        components: {
            ElButton: Button,
            ElRow: Row,
            ElCol: Col,
            ElInput: Input,
            ElDatePicker: DatePicker,
            MessageBox
        },
        created() {
            if (!localStorage.getItem('user')) {
                (async () => {
                    try {
                        let val = await MessageBox.prompt('您还没有注册，马上给自己整一个代号吧', {
                            inputPattern: /^.{1,20}$/,
                            inputErrorMessage: '亲，用户名长度要在1~20个字符内'
                        });
                        let res = await register({user: val.value});
                        localStorage.setItem('user', res.data.user);
                        location.reload();
                    } catch (e) {
                        if (e.response) {
                            MessageBox.alert(e.response.data.msg).then(() => location.reload());
                            console.error(e.response.data.msg)
                        }
                    }
                })()
            } else {
                this.getSurplus();
            }
        },
        methods: {
            async overtimes(hours) {
                try {
                    await setOvertime({hours});
                    this.getSurplus();
                } catch (e) {
                    console.error(e)
                }
            },
            async getSurplus() {
                let res = await surplus();
                this.lastMonthSurplus = res.lastMonthSurplus;
                this.thisMonthSurplus = res.thisMonthSurplus
            }
        }
    }
</script>

<style>
    .home {
        width: 90%;
        margin: auto;
        text-align: center;
        font-family: "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
    }

    .primary-text {
        color: #606266;
        text-align: center;
    }
</style>