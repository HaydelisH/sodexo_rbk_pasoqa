USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_CopiaEmpleados]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_CopiaEmpleados]
AS
BEGIN
	

  MERGE [SMU_Gestor].[dbo].[personas]  AS Target 
	USING 
	(  
		SELECT distinct rut
			  ,[nombre]
		  FROM [dbo].Tmp_Carga
		  group by rut, nombre 
		  having COUNT(Rut) = 1  
   ) 
		AS Source ON ( Target.[personaid] = Source.[Rut]   
	) 
	WHEN MATCHED THEN UPDATE SET  
		 Target.Nombre = Source.[nombre]

	WHEN NOT MATCHED BY TARGET THEN 
		INSERT ( 
				[personaid]
				,[nombre]
		)
		VALUES (
			  Source.[Rut]			  
			  ,Source.[nombre]	

		);




	
		
 MERGE SMU_Gestor.[dbo].[empleados]  AS Target 
	USING 
	(   SELECT  
		[Rut]
		,[RutEmpresa] AS Empresa
      ,[CodDivPersonal]
      , CASE Rol WHEN 'Publico' THEN 0
				 WHEN 'Privado' THEN 1
		END as Rolid
		,CASE [Estado] WHEN 'Activos' THEN 0
				 WHEN 'Finiquitados' THEN 1
		END as estado
		FROM [dbo].Tmp_Carga Emp

   ) 
		AS Source ON ( Target.[empleadoid] = Source.[Rut]   --and target.empresaid = source.Empresa
	) 
	WHEN MATCHED THEN UPDATE SET  
		Target.[centrocostoid]= Source.[CodDivPersonal]	,
		Target.estado= Source.estado,	
		Target.Rolid= Source.Rolid	

	WHEN NOT MATCHED BY TARGET THEN 
		INSERT ( 
				[empleadoid]
				  ,[empresaid]
				  ,[centrocostoid]	  
				  ,[rolid]
				  ,estado
					)
		VALUES (
			  Source.[Rut]
			  ,Source.Empresa 
			  ,Source.[CodDivPersonal]  
			  ,Source.Rolid
			  ,Source.estado
		);				


END
GO
