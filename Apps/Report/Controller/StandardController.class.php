<?php

namespace Report\Controller;
class StandardController extends WebInfoController
{
    //迭代评估
    public function index()
    {
        $var='
        <h3>方案一：需求迭代</h3>

            &nbsp;&nbsp;<b>标准评估里程碑：</b><br />
               &nbsp;&nbsp; &nbsp;&nbsp; 1. 冒烟测试用例<br />
                &nbsp;&nbsp;&nbsp;&nbsp; 2. 准入验收（单次不超过4小时）<br />
                &nbsp;&nbsp;&nbsp;&nbsp; 3. 第一轮测试（不超过8小时，可以由多个人参与）<br />
                &nbsp;&nbsp;&nbsp;&nbsp; 4. 第二轮测试（不超过8小时，可以由多个人参与）<br />
                &nbsp;&nbsp;&nbsp;&nbsp; 5. 第三轮测试（不超过8小时，非必须，可以由多个人参与）<br />
                &nbsp;&nbsp;&nbsp;&nbsp; 6. 预发环境验证<br />
                &nbsp;&nbsp;&nbsp;&nbsp; 7. 线上环境验证<br />    
                
                     
        <b><red>注意：从准入验收通过到达到上线标准，尽量控制一周之内</red></b>
        <h3>方案二：BUG迭代（单功能优化）</h3>
            &nbsp;&nbsp;<b>简化评估里程碑：</b><br />
                &nbsp;&nbsp;&nbsp;&nbsp; 1. 准入验收（单次不超过4小时）<br />
                &nbsp;&nbsp;&nbsp;&nbsp; 2. 预发环境验证<br />
                &nbsp;&nbsp;&nbsp;&nbsp; 3. 线上环境验证<br />
                
        <b><red>注意：尽量在准入验收通过当天上线</red></b>
    ';

        $this->assign('var', $var);

        $this->display();
    }
    //正常迭代
    public function manage(){
        $var=array(
            '1.周二，负责人参加需求讨论会，把迭代信息维护进禅道当中',
            '2.周三，下班前所有测试人员按照抽签的方式决出迭代负责人',
            '3.周四，迭代负责人参加需求评审会，了解详细需求',
            '4.周五，<br>
                &nbsp;&nbsp;1）Jira中按照评估规范给出里程碑<br>
                &nbsp;&nbsp;2）禅道中精确给出迭代的上线日期<br>
                &nbsp;&nbsp;3）禅道中细化测试任务，并分派给相关责任人（C类以上才迭代，至少2人参与）<br>
                &nbsp;&nbsp;4）整理本次迭代中改动和影响的功能点，并拉取到本期迭代当中',
            '5.开发提测前一天<br>
                &nbsp;&nbsp;1）整理完成本次迭代的原始需求<br>
                &nbsp;&nbsp;2）提供冒烟用例，测试用例必须包含步骤，和默认的测试数据<br>
                &nbsp;&nbsp;3）梳理本次迭代的必测点（需要测试的场景流程）',
            '6.提测当天<br>
                 &nbsp;&nbsp;1）超过提测规定的时间开发未完成提交预演，需要发邮件预警并记录到QC平台的迭代风险模块供<br>
                 &nbsp;&nbsp;2）在迭代的版本管理中维护对应平台的版本号以供BUG提交时使用',
            '7.测试过程中<br>
                 &nbsp;&nbsp;1）每天按照测试的任务，下班前精确记录当天的工时（不加班的话最多8小时，原则按实际情况填写）<br>
                 &nbsp;&nbsp;2）按照必测点，记录需要测试的没一个过程，留下每次的场景测试记录<br>
                 &nbsp;&nbsp;3）提倡写用例（禅道中），不必要太过详细，把必要动作和检查点留下即可<br>
                 &nbsp;&nbsp;5）严格对照产品原型和效果图进行测试，原型上的没一个文案和提示都要进行对照，只要不符就是BUG（当然可以是产品设计的BUG）<br>
                 &nbsp;&nbsp;6）提倡执行用例时留下执行记录，这样也可以简化BUG的提交流程，同时从侧面记录下发开人员的发开质量<br>
                 &nbsp;&nbsp; &nbsp;&nbsp;（比如同一个问题被修改了N边，每次的问题还不一样。这些只靠BUG分析是体现不出来的）<br>
                 &nbsp;&nbsp;7）所有问题或者BUG必须记录在按，不必每次找开发或产品人员确认，允许BUG被打回',
            '8.预发环境测试：<br>
                &nbsp;&nbsp;1）原则上要求预发环境安排在同一天完成<br>
                &nbsp;&nbsp;2）预发环境原则上是不允许有较多的程序BUG产生的，<br>
                &nbsp;&nbsp;&nbsp;&nbsp;（如果有需要改代码才能修复的BUG出现，必须在迭代风险汇总记录并择期召开项目总结会）',
            '9.线上环境测试：<br>
                &nbsp;&nbsp;1）配合产品完成线上的测试验证<br>
                &nbsp;&nbsp;2）发布测试过的必测点（未发布的，认为所有环节都没有问题）<br>
                &nbsp;&nbsp;3）严格把控上线的过程，如果上线完成在第二天凌晨以后，记录迭代风险，择期召开项目总结会<br>
                &nbsp;&nbsp;4）到计划上线日期没有完成的上线（或不可能完成上线的，尽早发出延期风险预警，并记录迭代风险）<br>
                &nbsp;&nbsp;5）迭代上线后，要督促相关责任人完成测试任务，负责人更新Jira的检查项状态，并将迭代任务拖至已上线<br>
                &nbsp;&nbsp;6）关闭禅道中的迭代'

        );
        $this->assign('var', $var);
        $this->display();
    }
    //紧急迭代（单功能优化）
    public function urgent(){
        $var=array(
            '1.临时紧急追加的迭代任务，由平台负责人自行在禅道中建立迭代（按照标题和简称规范）',
            '2.平台负责人，直接分派给迭代负责人（目前先按分派执行，以后打算按抢单的模式来）',
            '3.迭代负责人，按照精简模式在Jira上给出排期的里程碑',
            '4.迭代负责人，更新禅道迭代最后日期',
            '5.迭代负责人，在禅道建立测试任务并分派给自己',
            '6.迭代负责人，整理原始需求进禅道',
            '7.迭代负责人，在QC平台维护本期变更和影响的功能点',
            '8.迭代负责人，梳理本次迭代的必测点',
            '9.按照计划开发未提测，同样需要发邮件预警，并在QC平台记录风险',
            '10.按照必测点，记录需要测试的没一个过程，留下每次的场景测试记录',
            '11.紧急迭代，可以简化或者跳过预发环节',
            '12.紧急迭代，建议冒烟验收完成后当天完成',
            '13.迭代负责人，在上线成功后，<br>
                &nbsp;&nbsp;1）发布测试过的必测点，<br>
                &nbsp;&nbsp;2）关闭禅道中的迭代，<br>
                    3）在Jira中把相应的主任务或故事拖至已上线。',

        );
        $this->assign('var', $var);
        $this->display();
    }

    public function score(){
        $var1=array(
            '1.为了规范测试流程和鼓励测试任务的记录',
            '2.形成半年度绩效考核的排序依据'
        );
        $this->assign('var1', $var1);

        $var2=array(
            '1.多承担任务，多得积分（鼓励大家承担项目）',
            '2.多贡献策略和技能用于应用的，多得积分',
            '3.按照规范执行测试任务的，多得积分',
            '4.造成项目延期的，扣积分',
            '5.上线质量差的，扣积分',
        );
        $this->assign('var2', $var2);

        $var3=array(
            '初级工程师：<span class="badge">200分</span>',
            '中级工程师：<span class="badge">150分</span>',
            '高级工程师：<span class="badge">100分</span>',
            '资深工程师：<span class="badge">50分</span>'
        );
        $this->assign('var3', $var3);

        $var4=array(
            'A类（30+个功能点）：<span class="badge">20分/10分</span>',
            'B类（20+个功能点）：<span class="badge">16分/8分</span>',
            'C类（10+功能点）：<span class="badge">10分/5分</span>',
            'D类（10个及以下的功能点）：<span class="badge">5分/0分</span>'
        );
        $this->assign('var4', $var4);

        $var5=array(
            '1.提供详细迭代必测点（场景功能点序列）<span class="badge">3分</span>',
            '2.提供详细用例 <span class="badge">3分</span>',
            '3.有详细测试用例执行记录 <span class="badge">5分</span>',
            '4.提供详细任务工时<span class="badge">3分</span>',
            '5.提供完善的必测点执行记录<span class="badge">10分</span>',
	        '6.每个自然月里有扣分记录<span class="badge">10分</span>.',
            '7.将学到的技能应用于实际项目并显著提高工作效率或质量<span class="badge">5-10分</span>'
        );
        $this->assign('var5', $var5);

        $var6=array(
            '1.测试的直接原因造成项目延期<span class="badge">-5分</span>',
            '2.有测试的因素造成项目延期<span class="badge">-3分</span>',
            '3.根据BUG的及时处理原则，有未及时复测BUG<span class="badge">-1分/个</span>',
            '4.C类以上项目独立完成<span class="badge">-3分</span>',
            '5.违反迭代评估规范进行项目周期评估<span class="badge">-3分</span>',
        );
        $this->assign('var6', $var6);

        $var7=array(
            '1.上线后有B类未知的BUG（以禅道记录为准）<span class="badge">-3分</span>',
            '2.上线后有主流程BUG（A类BUG）<span class="badge">-5分</span>'
        );
        $this->assign('var7', $var7);

        $var8=array(
            '1.项目排期后未在禅道和Jira建立里程碑检查项<span class="badge">-3分</span>',
            '2.未及时更新禅道和Tower任务和里程碑检查项<span class="badge">-1分</span>',
            '3.迭代中未整理和关联需求<span class="badge">-1分</span>',
            '4.未按时提供冒烟用例<span class="badge">-3分</span>',
            '5.项目风险未及时披露或未在QC平台记录<span class="badge">-3分</span>',
            '6.BUG记录描述不明确（迭代、影响版本、模块，指派人等信息）<span class="badge">-1分</span>',
        );
        $this->assign('var8', $var8);

        $var9=array(
            '1.恶意修改和伪造评分记录（扣为0分，不在参与本半年度考核项目）<span class="badge">有多少扣多少</span>',
            '2.未按时完成分派任务或填写任务工时<span class="badge">-1</span>'
        );
        $this->assign('var9', $var9);

        $this->display();

    }
    //测试BUG
    public function bug(){
        $var1=array(
            '<b>开发人员：</b>当天17:30之前的BUG，必须当天进行确认（非BUG退回，是BUG确认，争议转产品经理）；<br>
            17：30以后的提交的BUG,可以第二天进行确认，尽量当天确定；<br>
            （温馨提示：BUG标记已解决的标准：开发负责部署的环境，部署到被测的环境才能标记已解决）',
            '<b>测试人员：</b>17:30前已解决的BUG，必须当天执行回归，17：30以后的解决的BUG,可以第二天进行回归，尽量当天回归；',
            '<b>产品经理：</b>17:30前指派给产品需要确认裁定的BUG，必须当天做出裁定，17：30以后的提交的BUG,可以第二天进行裁定，尽量当天裁定；'
        );
        $this->assign('var1', $var1);

        $var2=array(
            '1.BUG提交标题必须简要明确',
            '2.没有关联测试记录的，一定要自己手工关联到对应的项目、模块、版本和需求信息（方便以后的分类汇总统计）',
            '3.必要的时候在BUG的实际结果正文处留下截图尽量不要用以附件的形式添加截图（以便尽可能的减少再次沟通）',
        );
        $this->assign('var2', $var2);

        $var3=array(
            'A：服务器崩溃、宕机、主链接404、主流程阻塞（影响接下来的流程执行），客户端有规律的崩溃（闪退）,需求所列功能缺失',
            'B：介于A和C之间的BUG',
            'C：界面BUG不影响功能实现',
            'D：测试的建议（它本身不界定的BUG）'
        );
        $this->assign('var3', $var3);

        $var4=array(
            '1.数字越低的越要有限解决，即1为最紧急，2为次紧急……',
            '2.默认情况为空，测试人员不做维护，这个字段只有在待解决的BUG量较大时，由产品经理维护',
            '3.开发人员依据标记优先解决紧急的BUG',
            '4.这个字段对A类BUG无效'
        );
        $this->assign('var4', $var4);

        $this->display();
    }
    //测试准入
    public function admittance(){
        $var=array(
            '1.测试人员计划提测的时间前（至少提前半天），提供本次迭代新功能的冒烟测试用例（即完全正向主流程用例）',
            '2.开发人员在提交测试时（和合并代码到Develop分支前），要通过冒烟测试，保证提交模块的基本功能可用（最正向的流程可以跑通）',
            '3.测试人员在提测以后以同样的的冒烟测试用例为标准进行准入验收',
            '4.如果冒烟用例全部通过，则接收测试版本进入测试环节',
            '5.如果有冒烟用例执行失败，将提测版本退回，认为开发人员没有按时间提测',
            '6.开发可以逐个功能分批次提交，测试人员在持续集成环境进行验证。这次迭代的所有功能均达到以上标准后方可定义为提交测试，然后再部署标准测试环境'
        );
        $this->assign('var', $var);
        $this->display();
    }
    //预警邮件模板
    public function warning(){
        $var=array(
            '<b>标题：</b>XXX迭代，未按计划时间提交测试，会造成项目可能延期的风险',
            '<b> 邮件正文：</b><br>
            XXX迭代，原计划XX月XX日（上午上班前\下午下班前）提测（附上Tower的截图），<br>
            截止到XXX时间仍未收到提测信息，因此会造成项目可能延期的风险!<br><br>
            请相关责任人务必回复邮件说明原因，并明确给出可以提测的时间节点。 '
        );
        $this->assign('var', $var);

        $var1=array(
            '<b>标题：</b>XXX迭代，测试准入验收未通过，提测版本打回，有项目可能延期的风险',
            '<b> 邮件正文：</b><br>
            XXX迭代，在按照给出的冒烟测试标准进行测试准入验收时，发现如下冒烟用例未通过<br>
            （附上未通过的冒烟用例列表）<br>
            造成XX等功能无法正常进行测试，从而也无法按照计划进入测试环节，会有项目可能延期的风险<br><br>
            请相关责任人务必回复邮件说明原因，并明确给出可以重新提测的时间节点。 '
        );
        $this->assign('var1', $var1);

        $var2=array(
            '<b>标题：</b>XXX迭代，未达到最基本的上线标准，项目已经延期',
            '<b> 邮件正文：</b><br>
            XXX迭代，原计划XX月XX日上线，截止到XX时间，<br>
            还有XX必测点没有时间验证<br>
            （附上必测点清单）<br>
            禅道中还有BUG没有关闭<br>
           （ 附上BUG列表）<br>
            完全达不到上线的最基本要求，项目已经延期<br><br>
            请产品经理或其他负责人紧急召开项目会议，商议下一步的解决方案 '
        );
        $this->assign('var2', $var2);

        $this->display();
    }
    //测试通过邮件模板
    public function pass(){
        $var=array(
            '<b>标题：</b>XXX迭代，已通过预发和线上环境的验证，上线成功',
            '<b> 邮件正文：</b><br>
            XXX迭代，已通过预发（或线上环境）的验证，上线成功!<br>
            附上测试覆盖的测试场景如下：<br>
            （覆盖场景（必测点）列表）<br>
            附上遗留的BUG清单：<br>
            (未关闭的BUG清单)<br>
            <br>
            感谢各位小伙伴的辛苦付出！         
             '
        );
        $this->assign('var', $var);

        $var1=array(
            '<b>标题：</b>XXX迭代，测试（预发）环境已通过验证，达到XX标准，可以进行预发（线上）环境的部署',
            '<b> 邮件正文：</b><br>
            XXX迭代，测试（预发）环境已通过验证，达到XX标准<br>
           附上测试覆盖的测试场景如下：<br>
            （覆盖场景（必测点）列表）<br>
            附上遗留的BUG清单：<br>
            (未关闭的BUG清单)<br>   
            <br>        
             再辛苦一下研发童鞋进行预发（线上）环境的部署'
        );
        $this->assign('var1', $var1);

        $this->display();
    }

}