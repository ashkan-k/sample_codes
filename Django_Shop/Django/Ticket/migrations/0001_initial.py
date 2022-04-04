# Generated by Django 3.2 on 2021-04-19 11:26

from django.db import migrations, models
import django.db.models.deletion


class Migration(migrations.Migration):

    initial = True

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='Ticket',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('title', models.CharField(max_length=50, verbose_name='عنوان')),
                ('status', models.BooleanField(default=True, verbose_name='وضعیت')),
                ('created_at', models.DateTimeField(auto_now_add=True, verbose_name='تاریخ ثبت')),
                ('updated_at', models.DateTimeField(auto_now=True, verbose_name='تاریخ ویرایش')),
            ],
            options={
                'verbose_name': 'تیکت',
                'verbose_name_plural': 'تیکت ها',
            },
        ),
        migrations.CreateModel(
            name='TicketQuestion',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('text', models.TextField(verbose_name='متن سوال')),
                ('created_at', models.DateTimeField(auto_now_add=True, verbose_name='تاریخ ثبت')),
                ('updated_at', models.DateTimeField(auto_now=True, verbose_name='تاریخ ویرایش')),
                ('ticket', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='Ticket.ticket', verbose_name='تیکت')),
            ],
            options={
                'verbose_name': 'سوال های تیکت',
                'verbose_name_plural': 'سوال های تیکت',
            },
        ),
        migrations.CreateModel(
            name='TicketAnswer',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('text', models.TextField(verbose_name='متن پاسخ')),
                ('created_at', models.DateTimeField(auto_now_add=True, verbose_name='تاریخ ثبت')),
                ('updated_at', models.DateTimeField(auto_now=True, verbose_name='تاریخ ویرایش')),
                ('question', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='Ticket.ticketquestion', verbose_name='سوال')),
                ('ticket', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='Ticket.ticket', verbose_name='تیکت')),
            ],
            options={
                'verbose_name': 'پاسخ تیکت',
                'verbose_name_plural': 'پاسخ های تیکت',
            },
        ),
    ]